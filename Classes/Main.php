<?php
use Readers\ProductsXMLReader;
use Writers\XMLWriter;

class Main
{
    const PARSE = 1;
    const WRITE = 2;

    protected $parsedData, $option, $parser, $writer;

    function __construct()
    {
        $sourceXmlPath = 'Data/products.xml';
        $resultXmlPath = 'Results/products.xml';

        $writer = new XMLWriter($resultXmlPath);
        $this->parser = new ProductsXMLReader($sourceXmlPath, $writer);
    }

    public function main()
    {
        $this->parser->parseXML();
    }
}