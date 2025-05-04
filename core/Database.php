<?php
class Database
{
    private static $instance = null;
    private $pdo;

    private function __construct($config)
    {
        $this->pdo = new PDO(
            $config['dsn'],
            $config['user'],
            $config['pass'],
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            $config = require __DIR__ . '/../config/config.php';
            self::$instance = new Database($config['db']);
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }
}
