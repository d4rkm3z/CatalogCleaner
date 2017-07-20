<?php

namespace Logs;

class Logs
{

    static public function write($message = '')
    {
        $filePath = self::getLogFilePath();
        file_put_contents($filePath, self::prepareMessage($message), FILE_APPEND);
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