<?php

namespace Reformers;

use Logs\Logs;

abstract class AbstractReformer
{
    protected $reformattedData;
    protected $rawData;
    protected $dataMap;

    abstract protected function setDataMap();

    protected function fillItem(&$value, $key)
    {
        try {
            $type = gettype($value);

            if ($type == 'string') {
                $caseName = trim($value) !== '*' ? $value : $key;
                $value = isset($this->rawData[$caseName]) ? $this->rawData[$caseName] : 'false';
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

    protected function fillRawData()
    {
        foreach ($this->dataMap as &$case) {
            foreach($case as $key => &$value) {
                $value = $this->fillItem($value, $key);
            }
        }
    }

    public function reformat($rawData)
    {
        $this->rawData = $rawData;
        $this->fillRawData();
        return $this->dataMap;
    }
}