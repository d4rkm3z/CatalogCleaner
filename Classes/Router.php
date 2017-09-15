<?php

use Helpers\Text;
use Logs\Logs;

class Router
{
    protected static function help(array $options): void
    {
        Logs::write(Text::reformatForCLI("Wrong action!", Text::ERROR)
            . Text::reformatForCLI("<br>Available options: "));
        $optionsKeys = array_keys($options);
        array_walk($optionsKeys, function ($val) {
            print(Text::reformatForCLI("- $val<br>"));
        });
    }

    public static function getClass($type)
    {
        $options = [
            'migration' => 'Migrations\Migration',
            'colors-map' => 'Controllers\ColorsMapController',
            'parse-xml' => 'Readers\ProductsXMLReader',
            'localize' => 'Controllers\LocalizerController'
        ];

        if (isset($options[$type])) {
            return new $options[$type]();
        }

        self::help($options);
        exit();
    }
}