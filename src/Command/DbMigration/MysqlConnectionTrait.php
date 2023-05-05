<?php

namespace App\Command\DbMigration;

trait MysqlConnectionTrait
{
    public function getMysqlConnection(): \PDO
    {
        return new \PDO('mysql:host=mysql;dbname=web_meetings;charset=UTF8', 'root', 'root');
    }
}