<?php

namespace Logs;

class Logs
{
    static public function write($message = '')
    {
        $filePath = self::getLogFilePath();
        $message = self::prepareMessage($message);
        print($message);
        file_put_contents($filePath, $message, FILE_APPEND);
    }

    protected function getLogFilePath()
    {
        $folderPath = 'Logs/';
        $fileName = date('d-m-Y') . '.log';

        return $folderPath . $fileName;
    }

    protected function prepareMessage($message)
    {
        return $message = date('H:i:s') . ": $message\n";
    }
}