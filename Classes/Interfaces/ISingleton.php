<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 20.07.17
 * Time: 18:50
 */

namespace Interfaces;

interface ISingleton
{
    public static function getInstance(): ISingleton;
}