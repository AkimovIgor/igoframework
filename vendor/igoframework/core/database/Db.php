<?php

namespace Igoframework\Core\Database;

use Igoframework\Core\Traits\TSingletone;

class Db
{
    use TSingletone;

    protected $pdo;

    private function __construct()
    {
        $db = require ROOT . '/config/db.php';
        $this->pdo = new \PDO($db['dsn'], $db['user'], $db['password'], $db['options']);
    }

    /**
     * Выполнить подготовленный запрос и вернуть результат
     *
     * @param  string $sql SQL-запрос
     * @param  array $params Массив подготовленных параметров
     *
     * @return boolean
     */
    public function execute($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Выполнить подготовленный запрос и вернуть данные всех строк
     *
     * @param  string $sql SQL-запрос
     * @param  array $params Массив подготовленных параметров
     *
     * @return array
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->prepare($sql);
        $res = $stmt->execute($params);
        if ($res) {
            return $stmt->fetchAll();
        }
        return [];
    }

    /**
     * Выполнить подготовленный запрос и вернуть данные одной сроки
     *
     * @param  string $sql SQL-запрос
     * @param  array $params Массив подготовленных параметров
     *
     * @return boolean
     */
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