<?php

namespace Controllers;

abstract class AbstractController implements IController
{
    protected function beforeAction(){}

    protected function afterAction(){}

    abstract protected function startAction();

    public function main()
    {
        $this->beforeAction();
        $this->startAction();
        $this->afterAction();
    }
}