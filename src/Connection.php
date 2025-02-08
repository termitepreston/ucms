<?php

namespace MicroCMS;

require_once __DIR__ . '/' . 'Functions.php';

class Connection
{

    private static $conn;

    public function connect(): \PDO
    {
        $params = parse_ini_file(joinPaths(__DIR__, 'database.ini'));


        if ($params === false) {
            throw new \Exception("Error reading database configuration.");
        }

        // Connect to postgresql
        $connStr = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $params['host'],
            $params['port'],
            $params['database'],
            $params['user'],
            $params['password']
        );

        $pdo = new \PDO($connStr);

        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    public static function get()
    {
        if (null === static::$conn) {
            static::$conn = new static();
        }

        return static::$conn;
    }

    protected function __construct() {}

    private function __clone() {}

    private function __wakeup() {}
}
