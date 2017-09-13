<?php

namespace Readers;

use Base\Configurator;
use Logs\Logs;
use Models\Colors;
use Reformers\Reformer;
use Writers\XMLWriter;
use XMLReader;

class ProductsXMLReader
{
    const NOTHING = 0;
    const PRODUCT = 1;
    const VARIANT = 2;

    public $type;

    protected $configFile = 'reformer.cleaner';
    protected $reformer, $xml, $writer, $colorsModel, $config, $parseVariants;

    function __construct()
    {
        $this->config = (new Configurator($this->configFile))->getConfig();

        $this->xml = new XMLReader();
        $this->reformer = new Reformer();
        $this->colorsModel = new Colors();
        $this->parseVariants = true;
    }

    public function openXML(string $filename)
    {
        $this->xml->open($this->config["path"]["from"] . $filename);
        $this->writer = new XMLWriter($this->config["path"]["to"] . $filename);
    }

    protected function parseXmlCase($nodeName)
    {
        $node = static::xmlToArray($this->xml->readInnerXML());
        if (count($node) == 0) return false;

        $node = $this->reformer->reformat($node);
        //self::beforeInsert($node);
        $this->writer->insertNode($node, $nodeName);

        return true;
    }

    protected function getNodeName()
    {
        return strtolower($this->xml->name);
    }

    protected function identifyNodeType()
    {
        $isProductStart = $this->xml->nodeType == XMLReader::ELEMENT && $this->getNodeName() == 'product';
        $isVariantStart = $this->parseVariants && !$isProductStart && $this->xml->nodeType == \XMLReader::ELEMENT && $this->getNodeName() == 'variant';

        if ($isProductStart) $this->type = self::PRODUCT;
        if ($isVariantStart) $this->type = self::VARIANT;
    }

    /**
     * Load products from xml file from eSales format
     *
     * @param $pathToXML
     * @return array
     */
    public function parseXML()
    {
        $i = 0;
        while ($i<200 && $this->xml->read()) {
            $this->type = self::NOTHING;
            $nodeName = strtolower($this->xml->name);

            $this->identifyNodeType();

            if ($this->type == self::PRODUCT || $this->type == self::VARIANT) {
                if ($this->parseXmlCase($nodeName)) $this->xml->next();
                else continue;

                $i++;
            }
        }

        $this->xml->close();
        unset($this->writer);
        Logs::write("The xml is parsed");
    }

    protected function beforeInsert(&$node)
    {
        if ($this->type == self::PRODUCT) {
            $this->colorsModel->insert($node);
        } elseif ($this->type == self::VARIANT) {
            $result = $this->colorsModel->getDb($node);
            $node = array_merge($node, $result);
        }
    }

    static protected function xmlToArray($xml, $attributes = false)
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