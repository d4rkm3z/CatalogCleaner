<?php

class Main
{
    public $modelAction;

    function __construct()
    {
        $this->modelAction = Factory::getClass($_GET['action']);
    }

    public function main()
    {
        print("Main.php: System is started<br>");
        $this->modelAction->run();
    }
}