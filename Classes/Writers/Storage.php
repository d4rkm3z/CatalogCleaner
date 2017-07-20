<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 19.07.17
 * Time: 16:26
 */

namespace Writers;

class Storage
{
    protected $table;
    protected $data;

    public function __construct($table)
    {
        $this->table = $table;
    }

    /**
     * @param $product_id
     * @param $elements
     *
     * 'products' => [
     *      $product_id => [
     *          $color_id => [
     *             'color' => Red,
     *             'color_id' => 123
     *          ]
     *      ]
     * ]
     */
    public function append($elements)
    {

    }

    public function getByKey($key){
        return $this->data[$key];
    }
}