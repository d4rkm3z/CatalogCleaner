<?php

use \Logs\Logs;
use \Exception;

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

    protected function isCommandLineInterface(): bool
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
        try {
            $this->mainClass = Router::getClass($this->arguments['action']);
            $this->mainClass->run();
        } catch(Exception $exception){
            Logs::write($exception->getMessage());
        }
    }
}