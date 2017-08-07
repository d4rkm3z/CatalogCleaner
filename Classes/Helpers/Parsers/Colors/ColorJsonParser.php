<?php

namespace Helpers\Parsers\Colors;

class ColorJsonParser extends AbstractColorParser
{
    protected $colors;

    public function __construct()
    {
        $this->readColorsFile();
    }

    public function parse($attributeName)
    {
        $colorName = $this->prepareColorName($attributeName);
        return $this->colors->$colorName;
    }

    protected function readColorsFile()
    {
        $this->colors = json_decode(file_get_contents('Data/colors.json'));
    }
}