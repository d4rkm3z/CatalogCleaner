<?php

namespace Helpers;

class Text
{
    public static function formatForConsole($text): string
    {
        return str_replace('
', '<br>', $text);
    }
}