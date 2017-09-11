<?php

namespace Readers;

use Base\Configurator;
use Logs\Logs;
use Models\Colors;
use Reformers\Reformer;
use Writers\XMLWriter;

class ProductsXMLReader
{
    const NOTHING = 0;
    const PRODUCT = 1;
    const VARIANT = 2;

    protected $configFile = 'reformer.cleaner';
    protected $reformer, $xml, $writer, $colorsModel, $config;

    function __construct()
    {
        $this->config = (new Configurator($this->configFile))->getConfig();

        $this->xml = new \XMLReader();
        $this->reformer = new Reformer();
        $this->colorsModel = new Colors();
        $this->writer = new XMLWriter($this->config["path"]["result"]);
        $this->xml->open($this->config["path"]["source"]);
    }

    public function run()
    {
        self::parseXML();
    }

    /**
     * Load products from xml file from eSales format
     *
     * @param $pathToXML
     * @return array
     */
    protected function parseXML()
    {
        $xml = $this->xml;
        $parseVariants = true;

        while ($xml->read()) {
            $nodeName = strtolower($xml->name);

            $isProductStart = $xml->nodeType == \XMLReader::ELEMENT && $nodeName == 'product';
            $isVariantStart = $parseVariants && !$isProductStart && $xml->nodeType == \XMLReader::ELEMENT && $nodeName == 'variant';

            if ($isProductStart) $type = self::PRODUCT;
            if ($isVariantStart) $type = self::VARIANT;

            if ($isProductStart || $isVariantStart) {
                $node = static::_xmlToArray($xml->readInnerXML());
                if (count($node) == 0) continue;

                $node = $this->reformer->reformat($node);
                self::beforeInsert($node, $type);
                $this->writer->insertNode($node, $nodeName);
                $xml->next();
            }
        }

        $xml->close();
        unset($this->writer);
        Logs::write("The xml is parsed");
    }

    protected function beforeInsert(&$node, $type = self::NOTHING)
    {
        if ($type == self::PRODUCT) {
            $this->colorsModel->insert($node);
        } elseif ($type == self::VARIANT) {
            $result = $this->colorsModel->getDb($node);
            $node = array_merge($node, $result);
        }
    }

    static protected function _xmlToArray($xml, $attributes = false)
    {
        $result = [];

        if (is_array($attributes)) {
            if (isset($attributes[0])) {
                $attributes = array_map('preg_quote', $attributes);
                $attrList = '|' . implode('|', $attributes);
            } else {
                $attrList = '';
            }
            $rule = 'product_key|variant_key' . $attrList;
        } else {
            $rule = '[^>]+';
        }

        preg_match_all('/<(' . $rule . ')>([^<]*)<\/\1>/', $xml, $matches);
        foreach ($matches[1] as $index => $attr) {
            $value = $matches[2][$index];
            $result[$attr] = $value;
        }

        return $result;
    }
}