<?php

namespace Helpers;

class EnvironmentValidator
{
    static public function isCommandLineInterface(): bool
    {
        return (php_sapi_name() === 'cli');
    }
}