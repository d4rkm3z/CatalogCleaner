<?php
use Parsers\XML\ProductsXMLParser;
use Writers\XMLConstruct;

class Main
{
    protected $pathToXML;
    protected $parsedData;
    protected $parser;
    protected $reformer;

    function __construct()
    {
        $this->parser = new ProductsXMLParser();
        $this->writer = new XMLConstruct();
    }

    protected function parseXMLFile(){
        $this->parser->initReader($this->pathToXML);
        $this->parser->parseXML();
    }

    protected function writeXML(){
        $this->writer->write();
    }

    /**
     * @param mixed $pathToXML
     */
    public function setPathToXML($pathToXML)
    {
        $this->pathToXML = $pathToXML;
    }

    public function main()
    {
//        $this->setPathToXML('Data/products.xml');
//        $this->parseXMLFile();

        $this->writeXML();
    }
}