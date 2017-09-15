<?php

namespace XmlComponents;

use Logs\Logs;

class Reformer implements IXmlComponent
{
    protected $reformattedData;
    protected $rawData;
    protected $dataMap;
    protected $counter;

    public function run($node)
    {
        $this->reformat($node);
    }

    public function reformat($rawData)
    {
        $this->rawData = $rawData;
        $this->setCounter();
        $this->filter();
        $this->rename();
        $this->insertNewAttributes();
        $this->sort();
        return $this->rawData;
    }

    protected function setCounter()
    {
        if ($this->validateProduct()) {
            $this->counter = $this->counter + 1;
        }
    }

    protected function validateProduct()
    {
        return isset($this->rawData['_type']) && $this->rawData['_type'] == 'product';
    }

    /**
     * Filters node data with numbers in the end by props
     * Algo:
     *  1. remove from color_16 number
     *  2. find in $props['color'] value with key color_16
     *
     * Example: 'color' => ['color_16'] return color_16 attribute from xml, but other colors_%number% are removed from rawData
     */
    protected function filter()
    {
        $props = [
            'color' => ['color_16'],
            'class' => ['class_16'],
            'group' => ['group_16'],
            'style' => ['style_16'],
            'in_sales' => ['in_sales_16'],
            'in_sales_bool' => ['in_sales_bool_16'],
            'current_price' => ['current_price_16'],
            'original_price' => ['original_price_16'],
            'product_description' => ['product_description_16'],
            'product_name' => ['product_name_16'],
            'brand_name' => ['brand_name_16'],
            'campaigns' => ['campaigns_16'],
            'campaign_names' => ['campaign_names_16'],
            'sort_date' => [],
        ];

        $this->rawData = array_filter($this->rawData, function ($value, $key) use ($props) {
            if (preg_match('/^.+[\d]+$/', $key)) {
                preg_match('/(^[\w].+[^\_\d])/', $key, $matches);
                $attr_name = $matches[0];

                if (isset($props[$attr_name])) {
                    return (in_array($key, $props[$attr_name]));
                }
            }
            return true;
        }, ARRAY_FILTER_USE_BOTH);
    }

    /**
     * Renames setted attributes by props
     */
    protected function rename()
    {
        $props = [
            'color_16' => 'color',
            'class_16' => 'class',
            'group_16' => 'group',
            'style_16' => 'style',
            'in_sales_16' => 'in_sales',
            'in_sales_bool_16' => 'in_sales_bool',
            'current_price_16' => 'current_price',
            'original_price_16' => 'original_price',
            'product_description_16' => 'product_description',
            'product_name_16' => 'product_name',
            'brand_name_16' => 'brand_name',
            'campaigns_16' => 'campaigns',
            'campaign_names_16' => 'campaign_names',
        ];

        array_walk_recursive($this->rawData, function ($value, $key) use ($props) {
            if (isset($props[$key])) {
                $this->rawData[$props[$key]] = $value;
                unset($this->rawData[$key]);
            }
        });
    }

    protected function insertNewAttributes()
    {
        if ($this->counter % 5 == 0 && $this->validateProduct() && $this->rawData['show'] == 'true') {
            $this->rawData['is_new'] = 'true';
        }

        if ($this->validateProduct()) {
            $this->rawData['locale'] = 'en_US';
        }
    }

    protected function sort()
    {
        ksort($this->rawData);
    }

    protected function fillRawData()
    {
        foreach ($this->dataMap as &$case) {
            foreach ($case as $key => &$value) {
                $value = $this->fillItem($value, $key);
            }
        }
    }

    protected function fillItem(&$value, $key)
    {
        try {
            $type = gettype($value);

            if ($type == 'string') {
                $caseName = trim($value) !== '*' ? $value : $key;
                $value = isset($this->rawData[$caseName]) ? trim($this->rawData[$caseName]) : '';
            } elseif ($type == 'array') {
                $value = $this->getArrayValue($value);
            }

            return $value;
        } catch (\Exception $e) {
            Logs::write($e->getMessage());
            exit();
        }
    }

    protected function getArrayValue($array)
    {
        $result = 'false';
        foreach ($array as $value) {
            if (isset($this->rawData[$value])) {
                $result = $this->rawData[$value];
                break;
            }
        }
        return $result;
    }
}