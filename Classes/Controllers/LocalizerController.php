<?php

namespace Controllers;

use Readers\ProductsXMLReader;

class LocalizerController extends AbstractController
{
    protected $xmlParser;

    public function __construct()
    {
        $this->xmlParser = new ProductsXMLReader();
    }

    protected function beforeAction()
    {
        $this->xmlParser->openXML('products.xml');
    }

    protected function startAction(){
        $this->xmlParser->parseXML();
    }
}