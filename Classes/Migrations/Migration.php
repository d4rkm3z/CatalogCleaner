<?php
/**
 * Created by PhpStorm.
 * User: sadovnikov
 * Date: 20.07.17
 * Time: 15:56
 */

namespace Migrations;

use Database\Connector;
use Exception;
use Logs\Logs;

class Migration
{
    protected $db;

    public function __construct()
    {
        $this->db = (new Connector())->getConnection();
    }

    public function run()
    {
        $operation = $_GET['op'] ?? 'help';

        switch ($operation) {
            case 'create':
                $this->create();
                break;
            case 'remove':
                $this->remove();
                break;
            default:
                $this->help();
                break;
        }
    }

    protected function create()
    {
        $this->runSqlScripts('create.sql');
    }

    protected function runSqlScripts($file)
    {
        $dirs = glob('Migrations/*', GLOB_ONLYDIR);
        foreach ($dirs as $dir) {
            $query = file_get_contents("$dir/$file");
            print ("Apply $dir/$file<br>");

            try {
                $this->db->query($query);
            } catch (Exception $exception) {
                Logs::write($exception->getMessage());
            }
        }
    }

    protected function remove()
    {
        $this->runSqlScripts('remove.sql');
    }

    protected function help()
    {
        echo file_get_contents('Pages/migration-help.php');
    }
}