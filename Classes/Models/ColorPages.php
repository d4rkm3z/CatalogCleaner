<?php

namespace Models;

use Base\Configurator;
use Helpers\Net\NetConnector;

class ColorPages extends Model
{
    protected $table = 'colors_pages';
    protected $configFile = 'parser.color';
    protected $config;
    protected $color;
    protected $page;

    public function __construct()
    {
        $this->config = (new Configurator($this->configFile))->getConfig();
        parent::__construct();
    }

    protected function getFromDatabase()
    {
        return $this->db->fetch("body", "WHERE name = '{$this->color}'");
    }

    protected function getFromSite()
    {
        $url = $this->config['site'] . $this->color;
        return NetConnector::query($url);
    }

    protected function append()
    {
        $this->db->append([
            'name' => $this->color,
            'body' => $this->page
        ]);
    }

    public function getBodyByColor(string $color)
    {
        $this->color = $color;

        $body = $this->getFromDatabase();
        if (!$body) {
            $this->page = $body = $this->getFromSite();
            $this->append();
        }

        return $body;
    }
}