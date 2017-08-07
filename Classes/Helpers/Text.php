<?php

namespace Helpers;

class Text
{
    public static function format($text): string
    {
        return str_replace('
', '<br>', $text);
    }
}