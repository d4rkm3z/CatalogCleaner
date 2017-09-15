<?php

namespace Controllers;

use XmlComponents\Reader;
use XmlComponents\XmlLocalizer;

class LocalizerController extends AbstractController
{
    protected $xmlParser;
    protected $xmlTransformer;

    public function __construct()
    {
        $this->xmlParser = new Reader();
    }

    protected function beforeAction()
    {
        $this->xmlParser->openXML('products.xml');
        $this->xmlParser->setXmlTransformer(new XmlLocalizer());
    }

    protected function startAction(){
        $this->xmlParser->parseXML();
    }
}