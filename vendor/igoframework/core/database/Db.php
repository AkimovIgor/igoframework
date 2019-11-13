<?php

namespace Igoframework\Core\Database;

class Db
{
    private static $instance;
    protected $pdo;

    private function __construct()
    {
        $db = require ROOT . '/config/db.php';
        $this->pdo = new \PDO($db['dsn'], $db['user'], $db['password'], $db['options']);
    }

    private function __clone() {}

    private function __wakeup() {}

    public static function getInstance()
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        return self::$instance = new self;
    }

    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
            return $stmt->fetchAll();
        }
        return [];
    }

    public function queryFetch($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
            return $stmt->fetch();
        }
        return [];
    }
}