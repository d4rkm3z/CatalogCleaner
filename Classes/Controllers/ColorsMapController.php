<?php

namespace Controllers;

use Helpers\Parsers\Colors\ColorPageParser;
use Helpers\Parsers\Colors\ColorJsonParser;
use Logs\Logs;
use Models\ColorsMap;

class ColorsMapController extends AbstractController
{
    protected $colorNames = [];
    protected $model;
    protected $parser;

    public function __construct()
    {
        $this->model = new ColorsMap();
    }

    protected function setJsonParser(){
        $this->parser = new ColorJsonParser();
    }

    protected function setPageParser(){
        $this->parser = new ColorPageParser();
    }

    protected function startAction()
    {
        $this->model->setColors();
        $this->setJsonParser();
        $this->model->findColorsHex($this->parser);

        /* Disable next block for off PageLoader */
        $this->model->setColors();
        $this->setPageParser();
        $this->model->findColorsHex($this->parser);
        /* Hey! Stop your comment here! */
    }

    protected function afterAction()
    {
        Logs::write("Hex is loaded");
    }
}