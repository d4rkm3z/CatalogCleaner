<?php

namespace Readers;

use Logs\Logs;
use Reformers\Reformer;
use Writers\XMLWriter;

class ProductsXMLReader
{
    protected $reformer, $xml, $writer;

    function __construct($sourceXmlPath, XMLWriter $writer)
    {
        $this->xml = new \XMLReader();
        $this->writer = $writer;
        $this->reformer = new Reformer();

        $this->xml->open($sourceXmlPath);
    }

    /**
     * Load products from xml file from eSales format
     *
     * @param $pathToXML
     * @return array
     */
    public function parseXML()
    {
        $xml = $this->xml;
        $parseVariants = true;

        while ($xml->read()) {
            $nodeName = strtolower($xml->name);

            $isProductStart = $xml->nodeType == \XMLReader::ELEMENT && $nodeName == 'product';
            $isVariantStart = $parseVariants && !$isProductStart && $xml->nodeType == \XMLReader::ELEMENT && $nodeName == 'variant';

            if ($isProductStart || $isVariantStart) {
                $node = static::_xmlToArray($xml->readInnerXML());
                $node = $this->reformer->reformat($node);
                $this->writer->insertNode($node, $nodeName);
                $xml->next();
            }
        }

        $xml->close();
        unset($this->writer);
        Logs::write("The xml is parsed");
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