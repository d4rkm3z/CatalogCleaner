<?php

namespace Models;

use Models\XML\Reader;
use Models\XML\Writer;

class Localizer extends BaseModel
{
    protected $writer;
    protected $reader;

    public function init()
    {
        $this->reader = new Reader();
        $this->writer = new Writer();
    }

    public function run()
    {
        $this->reader->read();

        $this->writer->write([]);
    }
}