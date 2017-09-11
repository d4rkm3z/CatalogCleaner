<?php

namespace Base;

use Traits\LoaderConfiguration;

class Configurator
{
    use LoaderConfiguration;

    protected $configFile;
    protected $config;

    public function __construct($configFile)
    {
        $this->configFile = $configFile;
        $this->config = $this->loadConfig($this->configFile);
    }

    public function getConfig(){
        return $this->config;
    }
}