<?php

namespace Base;


class Configurator
{
    protected $configFile;
    protected $config;

    public function __construct($configFile)
    {
        $this->configFile = $configFile;
        $this->config = $this->loadConfig($this->configFile);
    }

    public function getConfig(string $className = "") : array
    {
        return $this->config[$className] ?? [];
    }

    protected function loadConfig($configFile): array
    {
        $basePath = 'Configurations/';
        return \yaml_parse_file("$basePath$configFile.yaml");
    }
}