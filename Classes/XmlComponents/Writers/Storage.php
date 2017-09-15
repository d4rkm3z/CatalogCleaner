<?php

namespace XmlComponents\Writers;

use Database\Connector;
use PDOException;
use PDOStatement;

class Storage
{
    protected $table;
    protected $data;
    protected $db;

    protected $elements;
    protected $stmt;
    protected $where;

    public function __construct($table)
    {
        $connector = new Connector();
        $this->stmt = new PDOStatement();

        $this->db = $connector->getConnection();
        $this->table = $table;
    }

    /**
     * Insert data from $elements to table of model by key => value
     *
     * @param $elements array
     */
    public function append(array $elements)
    {
        $this->elements = $elements;
        $this->validate();
        $this->prepareInsertQuery();
        $this->bindParams();
        $this->stmt->execute();
    }

    protected function validate()
    {
        if (!(is_array($this->elements) && count($this->elements)))
            throw new PDOException("elements is empty!");
    }

    /**
     * Prepare PDO insert query
     */
    protected function prepareInsertQuery()
    {
        $query = "INSERT INTO {$this->table} ({$this->getKeys()}) VALUES ({$this->getKeys(':')})";
        $this->stmt = $this->db->prepare($query);
    }

    /**
     * Return keys as key for PDO query
     *
     * @param string $after
     * @return string
     */
    protected function getKeys($after = ""): string
    {
        $keys = array_keys($this->elements);
        array_walk($keys, function (&$val) use ($after) {
            $val = $after . $val;
        });
        return implode(',', $keys);
    }

    /**
     * Bind params for PDO query
     */
    protected function bindParams()
    {
        foreach ($this->elements as $key => $value) {
            $this->stmt->bindValue(":$key", $value);
        }
    }

    public function updateMultiple(array $elements, string $where = "")
    {
        $this->elements = $elements;
        $this->where = $where;
        $this->validate();
        $this->prepareUpdateQuery(true);
        $this->stmt->execute();
    }

    protected function prepareUpdateQuery(bool $case = false)
    {
        $query = "UPDATE {$this->table} SET ";
        foreach ($this->elements as $key => $val) {
            $query .= $case ? "$key=$val " : "$key='$val' ";
        }
        $query .= $this->where;

        $this->stmt = $this->db->prepare($query);
    }

    public function update(array $elements, string $where = "")
    {
        $this->elements = $elements;
        $this->where = " WHERE " . $where;
        $this->validate();
        $this->prepareUpdateQuery();
        $this->stmt->execute();
    }

    public function fetchAllByColumn(array $elements = ['*'], string $additionallyQuery = "", $column = "")
    {
        $query = "SELECT " . implode(',', array_values($elements)) . " FROM {$this->table} $additionallyQuery";
        $this->stmt = $this->db->prepare($query);
        $this->stmt->execute();
        return $this->stmt->fetchAll(7, $column);
    }

    public function fetch(string $element, string $where = "")
    {
        $query = "SELECT $element FROM {$this->table} $where";
        $this->stmt = $this->db->prepare($query);
        $this->stmt->execute();
        return $this->stmt->fetch();
    }
}