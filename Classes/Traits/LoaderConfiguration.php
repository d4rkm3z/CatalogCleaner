<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 20.07.17
 * Time: 18:39
 */

namespace Traits;


trait LoaderConfiguration
{
    public function loadConfig($configFile) : array
    {
        $basePath = 'Configurations/';
        return yaml_parse_file($basePath . "$configFile.yaml");
    }
}