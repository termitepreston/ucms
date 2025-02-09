<?php

namespace MicroCMS;

require_once __DIR__ . '/' . 'Functions.php';

class Connection
{

    private static ?Connection $conn = null;

    public function connect(): \PDO
    {
        $dbHost = $_ENV['DB_HOST'];
        $dbUser = $_ENV['DB_USER'];
        $dbPassword = $_ENV['DB_PASSWORD'];
        $dbName = $_ENV['DB_NAME'];


        $pdo = new \PDO("pgsql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword);

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function getInstance(): Connection
    {
        if (null === static::$conn) {
            static::$conn = new static();
        }

        return static::$conn;
    }

    protected function __construct() {}

    /**
     * Prevent cloning of the instance.
     *
     * @throws \Exception If cloning is attempted.
     */
    private function __clone()
    {
        throw new \Exception("Cannot clone a Connection instance.");
    }

    /**
     * Prevent un-serialization of the instance.
     *
     * @throws \Exception If un-serialization is attempted.
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a Connection instance.");
    }
}
