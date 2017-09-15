<?php

namespace XmlComponents;

interface IXmlComponent
{
    /**
     * Main entry point to XmlComponent
     * @param $node XmlNode
     * @return mixed
     */
    public function run($node);

    public function getNodes();
}