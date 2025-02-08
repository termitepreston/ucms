<?php

namespace MicroCMS;

class Connection
{

    private static $conn;

    public function connect()
    {
        $params = parse_ini_file('database.ini');


        if ($params !== false) {
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
    }

    public static function get()
    {
        if (static::$conn === null) {
            static::$conn = new static();
        }

        return static::$conn;
    }

    protected function __construct() {}

    private function __clone() {}

    private function __wakeup() {}
}
