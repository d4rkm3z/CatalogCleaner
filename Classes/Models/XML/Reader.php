<?php

namespace Models\XML;

use Logs\Logs;
use XMLReader;

class Reader extends XmlModel
{
    const NOTHING = 0;
    const PRODUCT = 1;
    const VARIANT = 2;

    public $type;

    protected $reformer, $xmlReader, $colorsModel, $parseVariants, $xmlTranformer;

    function init()
    {
        $this->parseVariants = $this->config['parseVariants'];
    }

    public function setXmlTransformer(IXmlComponent $xmlTransformer)
    {
        $this->xmlTranformer = $xmlTransformer;
    }

    public function openXML()
    {
        $this->initReader();
    }

    protected function initReader()
    {
        $this->xmlReader = new XMLReader();
        $this->xmlReader->open($this->filePath);
    }

    protected function runXmlOperations($node)
    {
        $this->xmlTranformer->run($node);
        $nodes = $this->xmlTranformer->getNodesStorage();
    }

    protected function parseXmlCase($nodeName)
    {
        $node = static::xmlToArray($this->xmlReader->readInnerXML());
        if (count($node) == 0) return false;

        $this->runXmlOperations($node);
        //self::beforeInsert($node);
        //$this->writer->insertNode($node, $nodeName);

        return true;
    }

    protected function getNodeName()
    {
        return strtolower($this->xmlReader->name);
    }

    protected function identifyNodeType()
    {
        $isProductStart = $this->xmlReader->nodeType == XMLReader::ELEMENT && $this->getNodeName() == 'product';
        $isVariantStart = $this->parseVariants && !$isProductStart && $this->xmlReader->nodeType == \XMLReader::ELEMENT && $this->getNodeName() == 'variant';

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
        while ($this->xmlReader->read()) {
            $this->type = self::NOTHING;
            $nodeName = strtolower($this->xmlReader->name);

            $this->identifyNodeType();

            if ($this->type == self::PRODUCT || $this->type == self::VARIANT) {
                if ($this->parseXmlCase($nodeName)) $this->xmlReader->next();
                else continue;
            }
        }

        $this->xmlReader->close();
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