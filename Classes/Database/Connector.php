<?php

namespace Database;

use PDO;
use PDOException;
use Traits\LoaderConfiguration;

class Connector
{
    use LoaderConfiguration;

    public $dbh;
    protected $configFile = 'db';
    protected $config;


    public function __construct()
    {
        $this->config = $this->loadConfig($this->configFile);
        $this->createConnection();
    }

    protected function createConnection()
    {
        try {
            $this->dbh = new PDO(
                $this->config['dsn'],
                $this->config['user'],
                $this->config['password']
            );
        } catch (PDOException $e) {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }

    public function getConnection(): PDO
    {
        return $this->dbh;
    }
}