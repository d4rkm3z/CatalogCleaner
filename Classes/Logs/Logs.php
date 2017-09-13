<?php

namespace Logs;

use Helpers\Text;
use \Helpers\EnvironmentValidator;

class Logs
{
    static public function write($message = '')
    {
        $filePath = self::getLogFilePath();
        $message = self::prepareMessage($message);

        print($message);
        file_put_contents($filePath, $message, FILE_APPEND);
    }

    static protected function getLogFilePath()
    {
        $folderPath = 'Logs/';
        $fileName = date('d-m-Y') . '.log';

        return $folderPath . $fileName;
    }

    static protected function prepareMessage($message)
    {
        $message = date('H:i:s') . ": $message\n";
        if (EnvironmentValidator::isCommandLineInterface()) $message = Text::reformatForCLI($message);

        return $message;
    }
}