<?php

namespace DB;

use \PDO;
use \PDOException;
use Logs\Logs;

class Connector
{
    const CONNECT_PARAMS = [
        'user' => 'root',
        'pass' => '12345',
        'db' => [
            'name' => 'demoCatalog',
            'host' => '127.0.0.1',
            'charset' => 'utf8'
        ]
    ];

    protected $dbh;
    protected $table;

    function __construct()
    {
        $this->createConnection();
    }

    protected function createConnection()
    {
        try {
            $this->dbh = new PDO(
                "mysql:host=" . static::CONNECT_PARAMS['db']['host'] .
                ";dbname=" . static::CONNECT_PARAMS['db']['name'] .
                ";charset=" . static::CONNECT_PARAMS['db']['charset'],
                static::CONNECT_PARAMS['user'],
                static::CONNECT_PARAMS['pass']
            );
        } catch (PDOException $e) {
            Logs::write($e->getMessage());
            exit();
        }
    }

    protected function implodeRows($rows)
    {
        return count($rows) ? implode(',', $rows) : '*';
    }

    protected function querySelect($rows = [])
    {
        return $this->dbh->query("SELECT {$this->implodeRows($rows)} from {$this->table}");
    }

    protected function quoteValues($array, $type = "values")
    {
        array_walk($array, function (&$item) use ($type) {
            $item = $type == "keys" ? "`$item`" : "'$item'";
        });
        return $array;
    }

    public function setTable($table)
    {
        $this->table = $table;
    }

    public function fetchAll($rows = [])
    {
        return $this->querySelect($rows)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query($queryString)
    {
        return $this->dbh->query($queryString);
    }

    public function insert($insertValues = [], $foreign = [])
    {
        $foreignColumns = count($foreign) > 1 ? ',' . implode(',', $this->quoteValues(array_keys($foreign), 'keys')) : '';
        $foreignQueries = count($foreign) > 1 ? ',' . implode(',', array_values($foreign)) : '';

        $columnsList = implode(',', $this->quoteValues(array_keys($insertValues), 'keys'));
        $valuesList = implode(',', $this->quoteValues(array_values($insertValues), 'values'));

        $this->dbh->query("INSERT IGNORE INTO {$this->table} ($columnsList $foreignColumns) VALUES($valuesList $foreignQueries)");
    }
}