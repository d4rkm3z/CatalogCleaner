<?php

class Factory
{
    public static function getClass($type)
    {
        $options = [
            'migration' => 'Migrations\Migration',
            'colors-parse' => 'Models\ColorsParser',
            'parse-xml' => 'Readers\ProductsXMLReader'
        ];

        if (isset($options[$type])) {
            return new $options[$type]();
        }

        print(Text::format("Wrong action!<br>Available options: <br>"));
        array_walk(array_keys($options), function ($val) {
            print ("- $val<br>");
        });
        exit();
    }
}