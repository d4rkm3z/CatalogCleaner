<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 19.07.17
 * Time: 16:41
 */

namespace Writers;

class XMLBase
{
    protected $filePath;

    protected function validateResultFile($remove = true)
    {
        if (file_exists($this->filePath) && $remove) {
            unlink($this->filePath);
        } elseif (!file_exists($this->filePath) && !$remove) {
            exit("- Error: Storage file is not created<br>");
        }
    }
}