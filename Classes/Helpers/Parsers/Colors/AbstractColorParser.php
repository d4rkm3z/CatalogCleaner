<?php

namespace Helpers\Parsers\Colors;

abstract class AbstractColorParser implements IColorsParser
{
    abstract public function parse($attributeName);

    protected function prepareColorName(string $colorName): string
    {
        return strtolower(str_replace(' ', '-', $colorName));
    }
}