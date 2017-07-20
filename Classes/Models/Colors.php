<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 19.07.17
 * Time: 16:26
 */

namespace Models;

use Writers\Storage;

class Colors
{
    private $colors;

    public function __construct()
    {
        $this->colors = new Storage('colors');
    }

    public function insert($writerData)
    {
        $this->colors->append([
            'product_key' => $writerData['product_key'],
            'name' => $writerData['color'],
            'color_id' => $writerData['color_id'],
        ]);
    }

    public function getColors($node)
    {
        return $this->colors->getByKey($node['product_key']);
    }

    public function __destruct()
    {
        $this->colors->save();
        echo('done');
    }
}