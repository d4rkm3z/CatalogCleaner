<?php
use Parsers\XML\ProductsXMLParser;
use Reformers\ProductReformer;

class Main
{
    protected $pathToXML;
    protected $parsedData;
    protected $parser;
    protected $reformer;

    function __construct()
    {
        $this->setParser(new ProductsXMLParser());
    }

    protected function setParser($parser)
    {
        $this->parser = $parser;
    }

    protected function parseXMLFile(){
        $this->parser->initReader($this->pathToXML);
        $this->parser->parseXML();
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
        $this->setPathToXML('Data/products.xml');
        $this->parseXMLFile();
    }
}