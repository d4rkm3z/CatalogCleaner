<?php

namespace Models\XML;

use Models\BaseModel;

class XmlModel extends BaseModel
{
    protected $filePath;

    protected function initPaths(){
        $this->filePath = $this->config['folder'] . $this->config['file'];
    }
}