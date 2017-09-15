<?php

namespace XmlComponents;

use Helpers\Text;

class XmlLocalizer implements IXmlComponent
{
    protected $limit = 2533;
    protected $nodesStorage = [];

    public function run($node)
    {
        if ($this->limit > 0) {
            $this->nodesStorage[] = $node;
            if (isset($this->rawData['_type']) && $this->rawData['_type'] == 'product') {
                $this->limit -= 1;
            }
        }
    }

    public function testStorage()
    {
        $count = count($this->nodesStorage);
        print Text::reformatForCLI("$count", Text::SUCCESS);
        var_dump($this->nodesStorage[$count - 1]);
    }

    public function getNodes()
    {
        return $this->nodesStorage;
    }

    public function __destruct()
    {
        $this->testStorage();
    }
}