<?php

namespace Parsers\XML;

abstract class AbstractParser
{
    protected $xml;

    abstract public function parseXML();

    /**
     * Convert simple xml to array [tag=>value]
     *
     * @param string $xml
     * @param array|bool $attributes
     *
     * @return array
     */
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

    public function initReader($pathToXML)
    {
        $this->xml = new \XMLReader();
        $this->xml->open($pathToXML);
    }
}