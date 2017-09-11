<?php

namespace Database;

use Base\Configurator;
use PDO;
use PDOException;

class Connector
{

    public $dbh;
    protected $configFile = 'db';
    protected $config;


    public function __construct()
    {
        $this->config = (new Configurator($this->configFile))->getConfig();
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