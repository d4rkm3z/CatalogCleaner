<?php

namespace Entrypoints;

use Database\Connector;
use Factory;

class Main
{
    protected $mainClass;
    protected $arguments;

    public function __construct($argv)
    {
        $this->initArguments($argv);
    }

    protected function initArguments($argv)
    {
        $this->arguments = $this->isCommandLineInterface() ?
            $this->setFromArgv($argv) :
            $this->setFromGet();
    }

    protected function isCommandLineInterface()
    {
        return (php_sapi_name() === 'cli');
    }

    protected function setFromArgv($argv): array
    {
        return [
            'action' => $argv[1],
        ];
    }

    protected function setFromGet(): array
    {
        return [
            'action' => $_GET['action'],
        ];
    }

    public function main(): void
    {
        $this->mainClass = Factory::getClass($this->arguments['action']);
        $this->mainClass->run();
    }
}