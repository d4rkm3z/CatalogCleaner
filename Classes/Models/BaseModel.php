<?php

namespace Models;

use Base\Configurator;

class BaseModel implements IModel
{
    protected $config;
    protected $configFile = "main.yaml";

    public function __construct()
    {
        $this->loadConfiguration();
        $this->init();
    }

    public function init()
    {
    }

    public function getClassName(){
        return get_class($this);
    }

    protected function loadConfiguration()
    {
        $this->config = (new Configurator($this->configFile))->getConfig($this->getClassName());
    }
}