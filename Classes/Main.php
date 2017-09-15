<?php

use Controllers\IController;
use Logs\Logs;
use \Helpers\EnvironmentValidator;

class Main
{
    protected $arguments;
    protected $isCLI = false;

    public function __construct($argv)
    {
        $this->isCLI = EnvironmentValidator::isCommandLineInterface();
        $this->initArguments($argv);
    }

    protected function initArguments($argv)
    {
        $this->arguments = $this->isCLI ?
            $this->setFromArgv($argv) :
            $this->setFromGet();
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

    public function runController(IController $controller)
    {
        $controller->main();
    }

    public function main(): void
    {
        try {
            $this->runController(Router::getClass($this->arguments['action']));
        } catch (Exception $exception) {
            Logs::write($exception->getMessage());
        }
    }
}