<?php

namespace Controllers;

use Helpers\Parsers\Colors\ColorPageParser;
use Helpers\Parsers\Colors\ColorJsonParser;
use Logs\Logs;
use Models\ColorsMap;

class ColorsMapController
{
    protected $colorNames = [];
    protected $model;
    protected $parser;

    public function __construct()
    {
        $this->model = new ColorsMap();
    }

    public function setJsonParser(){
        $this->parser = new ColorJsonParser();
    }

    public function setPageParser(){
        $this->parser = new ColorPageParser();
    }

    public function run()
    {
        $this->model->setColors();
        $this->setJsonParser();
        $this->model->findColorsHex($this->parser);

        /* Disable next block for off PageLoader */
        $this->model->setColors();
        $this->setPageParser();
        $this->model->findColorsHex($this->parser);
        /* Hey! Stop your comment here! */

        Logs::write("Hex is loaded");
    }
}