<?php

namespace Models;

use Writers\Storage;

class Model
{
    protected $table;
    protected $db;

    public function __construct()
    {
        $this->db = new Storage($this->table);
    }

    public function getDb($node)
    {
        return $this->db->getByKey($node['product_key']);
    }
}