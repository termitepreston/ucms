<?php

namespace MicroCMS;

class Migration
{
    private \PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function configure()
    {
        $sql = 'CREATE EXTENSION IF NOT EXISTS "uuid-ossp";';

        $this->pdo->exec($sql);

        return $this;
    }

    /**
     * @throws PDOException.
     */
    public function tearDown()
    {
        // Create users table.
        $sqlList = [
            'DROP TABLE IF EXISTS USERS;'
        ];

        foreach ($sqlList as $sql) {
            $this->pdo->exec($sql);
        }

        return $this;
    }

    public function createTables()
    {
        $sqlList = [
            'CREATE TABLE IF NOT EXISTS USERS
            (

            )'
        ];
    }
}
