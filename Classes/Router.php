<?php

use Helpers\Text;

class Router
{
    protected static function help(array $options): void
    {
        print(Text::format("Wrong action!<br>Available options: <br>"));
        array_walk(array_keys($options), function ($val) {
            print ("- $val<br>");
        });
    }

    public static function getClass($type)
    {
        $options = [
            'migration' => 'Migrations\Migration',
            'colors-map' => 'Controllers\ColorsMapController',
            'parse-xml' => 'Readers\ProductsXMLReader'
        ];

        if (isset($options[$type])) {
            return new $options[$type]();
        }

        self::help($options);
        exit();
    }
}