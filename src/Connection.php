<?php

namespace MicroCMS;

require_once __DIR__ . '/' . 'Functions.php';

class Connection
{

    private static ?Connection $conn = null;

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
