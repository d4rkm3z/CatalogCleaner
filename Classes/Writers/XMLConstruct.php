<?php

namespace Writers;

use DB\Fetcher;

class XMLConstruct extends \XMLWriter
{
    protected $filePath;
    protected $data;
    protected $db;
    protected $xmlWriter;

    public function __construct()
    {
        $this->filePath = 'Results/product.xml';
        $this->db = new Fetcher();
    }

    protected function initWriter()
    {
        $this->xmlWriter = new \XMLWriter();
        $this->xmlWriter->openMemory();
        $this->xmlWriter->startDocument('1.0', 'UTF-8');
    }

    protected function fetchData()
    {
        $this->data = $this->db->fetchAllProducts();
    }

    protected function insertNode($cell)
    {
        $this->xmlWriter->startElement('product');
        foreach ($cell as $key=>$value) {
            $this->xmlWriter->writeElement($key, $value);
        }
        $this->xmlWriter->endElement();
    }

    protected function assembleDocument()
    {
        $count = count($this->data);
        $xmlWriter = $this->xmlWriter;
        $xmlWriter->startElement('operations');
        $xmlWriter->startElement('update');

        for ($i = 0; $i <= $count; ++$i) {
            $this->insertNode($this->data[$i]);

            if (0 == $i % 1000) {        // Flush XML in memory to file every 1000 iterations
                $this->writeContent();
            }
        }

        $xmlWriter->endElement();  //Close <update>
        $xmlWriter->endElement();  //Close <operation>
        $this->writeContent();  // Final flush to make sure we haven't missed anything
    }

    protected function writeContent()
    {
        file_put_contents($this->filePath, $this->xmlWriter->flush(true), FILE_APPEND);
    }

    public function write()
    {
        unlink($this->filePath);
        $this->fetchData();
        $this->initWriter();
        $this->assembleDocument();
    }
}