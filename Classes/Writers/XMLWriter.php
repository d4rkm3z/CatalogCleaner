<?php

namespace Writers;

use Logs\Logs;

class XMLWriter extends XMLBase
{
    protected $filePath;
    protected $xmlWriter;

    public function __construct($resultFilePath)
    {
        $this->filePath = $resultFilePath;
        $this->validateResultFile();
        $this->initWriter();

        $this->xmlHeader();
    }

    protected function initWriter()
    {
        $this->xmlWriter = new \XMLWriter();
        $this->xmlWriter->openMemory();
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->startDocument('1.0', 'UTF-8');
    }

    protected function xmlHeader()
    {
        /** [+]operations instruction */
        $this->xmlWriter->startElement('operations');
        /** [+]clear instruction */
        $this->xmlWriter->startElement('clear');
        $this->xmlWriter->writeElement('product');
        $this->xmlWriter->endElement();
        /** [-]clear instruction */
        /** [+]add instruction */
        $this->xmlWriter->startElement('add');
    }

    function __destruct()
    {
        $this->xmlBottom();
    }

    protected function xmlBottom()
    {
        $this->xmlWriter->endElement();
        /** [-]add instruction */
        $this->xmlWriter->endElement();
        /** [-]operations instruction */

        $this->writeContent();

        Logs::write("File is parsed");
        print ('xml_end<br/>');
    }

    protected function writeContent()
    {
        file_put_contents($this->filePath, $this->xmlWriter->flush(true), FILE_APPEND);
    }

    public function insertNode($cell, $nodeName)
    {

        if (count($cell) == 0) return;
        $this->xmlWriter->startElement($nodeName);
        $this->xmlWriter->startElement();
        foreach ($cell as $key => $value) {
            $this->xmlWriter->writeElement($key, trim($value));
        }
        $this->xmlWriter->endElement();
        $this->writeContent();
    }
}