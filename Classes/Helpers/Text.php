<?php

namespace Helpers;

class Text
{
    const NOTHING = 0;
    const ERROR = 1;
    const SUCCESS = 2;

    protected static function colorFactory($type){
        switch ($type) {
            case self::ERROR:
                $color = "\e[31m";
                break;
            case self::SUCCESS:
                $color = "\033[32m";
                break;
            default:
                $color = "";
        }

        return $color;
    }

    public static function reformatForCLI($text, $type = self::NOTHING): string
    {
        $message = str_replace("<br>", "\n", $text);
        if ($type !== self::NOTHING){
            $message = self::colorFactory($type) . " $message \033[0m";
        }
        return $message;
    }
}