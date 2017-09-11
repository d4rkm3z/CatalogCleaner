<?php

use \Logs\Logs;
use \Exception;
use \Controllers\IController;

class Main
{
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

    public function runController(IController $controller){
        $controller->main();
    }

    public function main(): void
    {
        try {
            $this->runController(Router::getClass($this->arguments['action']));
        } catch(Exception $exception){
            Logs::write($exception->getMessage());
        }
    }
}