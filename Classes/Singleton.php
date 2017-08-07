<?php

use Interfaces\ISingleton;

abstract class Singleton implements ISingleton
{

    private static $_instances = [];

    final private function __construct()
    {
        $this->init();
    }

    final public static function getInstance(): ISingleton
    {
        $className = get_called_class();
        self::$_instances[$className] = self::$_instances[$className] ?? new static();
        return self::$_instances[$className];
    }

    final private function __clone()
    {
        throw new Exception("Can't clone a singleton");
    }

    final private function __wakeup()
    {
    }
}