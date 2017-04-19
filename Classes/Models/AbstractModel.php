<?php

namespace Models;

use DB\Connector;

abstract class AbstractModel
{
    protected $table;
    protected $db;

    function __construct()
    {
        $this->setDBTable($this->getClassName());
        $this->setDbConnection();
    }

    private function setDBTable($name)
    {
        $this->table = str_replace('model', '', strtolower($name));
    }

    private function setDbConnection()
    {
        $this->db = new Connector();
    }

    protected function getClassName()
    {
        preg_match("/\\\([\w]+)/xu", get_class($this), $result);
        return $result[1];
    }

    /**
     * This function simple wrapper for database insert function
     *
     * @param array $rows - array with attributes for insert to DB.
     * It has next structure: ['column_name' => 'inserted_value']
     *
     * @param array $where - array with where parameters for mysql insert query
     */
    public function insert($rows = [], $where = [])
    {
        $this->db->insert($rows, $where);
    }

    public function getAllRows()
    {
        return $this->db->fetchAll();
    }
}