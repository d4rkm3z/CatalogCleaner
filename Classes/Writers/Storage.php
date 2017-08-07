<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 19.07.17
 * Time: 16:26
 */

namespace Writers;

use Database\Connector;

class Storage
{
    protected $table;
    protected $data;
    protected $db;

    protected $elements;
    protected $stmt;

    public function __construct($table)
    {
        $connector = new Connector();
        $this->db = $connector->getConnection();
        $this->table = $table;
    }

    protected function getKeys($after = "")
    {
        $keys = array_keys($this->elements);
        array_walk($keys, function (&$val) use ($after) {
            $val = $after . $val;
        });
        return implode(',', $keys);
    }

    protected function bindParams()
    {
        foreach ($this->elements as $key => $value) {
            $this->stmt->bindParam(":$key", $value);
        }
    }

    protected function prepareQuery()
    {
        $query = "INSERT INTO {$this->table}  ({$this->getKeys()}) VALUES ({$this->getKeys(':')})";
        $this->stmt = $this->db->prepare($query);
    }

    /**
     * @param $product_id
     * @param $elements
     *
     */
    public function append($elements)
    {
        $this->elements = $elements;
        $this->prepareQuery();
        $this->bindParams();
        $this->stmt->execute();
        exit();
    }

    public function getByKey($key)
    {
        return $this->data[$key];
    }
}