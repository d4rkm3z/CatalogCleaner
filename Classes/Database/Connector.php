<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 20.07.17
 * Time: 15:50
 */

namespace Database;

use \Singleton;
use \PDO;
use \PDOException;
use Traits\LoaderConfiguration;

class Connector extends Singleton
{
    use LoaderConfiguration;

    protected $configFile = 'db';
    protected $config;

    public $dbh;

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
}