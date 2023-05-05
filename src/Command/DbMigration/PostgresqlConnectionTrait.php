<?php

namespace App\Command\DbMigration;

trait PostgresqlConnectionTrait
{
    public function getPostgresqlConnection(): \PDO
    {
        return new \PDO('pgsql:host=postgres;dbname=development;charset=UTF8', 'development', 'password');
    }
}