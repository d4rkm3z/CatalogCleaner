<?php

use Models\ColorsLoader;
use Readers\ProductsXMLReader;

class Factory
{
    public static function getClass($type)
    {
        switch ($type) {
            case 'migration':
                return new Migrations\Migration();
            case 'colors-load':
                return new ColorsLoader();
            default:
                exit();
                return new ProductsXMLReader();
        }
    }
}