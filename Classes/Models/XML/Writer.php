<?php

namespace Models\XML;

use Logs\Logs;

class Writer extends XmlModel
{
    protected $filePath;
    protected $xmlWriter;

    protected function validateResultFile($remove = true)
    {
        if (file_exists($this->filePath) && $remove) {
            unlink($this->filePath);
        } elseif (!file_exists($this->filePath) && !$remove) {
            exit("- Error: Storage file is not created<br>");
        }
    }

    public function init()
    {
        $this->xmlWriter = new \XMLWriter();
        $this->filePath = $this->config['file'];
        $this->validateResultFile();
        $this->xmlWriter->openMemory();
        $this->xmlWriter->setIndent(true);
        $this->xmlWriter->startDocument('1.0', 'UTF-8');
        $this->xmlHeader();
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
        foreach ($cell as $key => $value) {
            $this->xmlWriter->writeElement($key, trim($value));
        }
        $this->xmlWriter->endElement();
        $this->writeContent();
    }
}