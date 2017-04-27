<?php

namespace Writers;

use DB\Connector;

class XMLWriter
{
    protected $endedFilePath;
    protected $db;

    public function __construct()
    {
        $this->endedFilePath = 'Results/product.xml';
        $this->db = new Connector();
    }

    protected function fetchAllData(){

    }

    public function writeToFile(){

    }
}