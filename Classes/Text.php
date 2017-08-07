<?php

class Text
{
    public static function format($text){
        return str_replace('
', '<br>', $text);
    }
}