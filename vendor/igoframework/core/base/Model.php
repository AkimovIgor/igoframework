<?php

namespace Igoframework\Core\Base;

use Igoframework\Core\Database\Db;

abstract class Model extends Db
{
    protected $pdo;             // объект PDO
    protected $table;           // текущая таблица
    protected $pKey = 'id';     // первичный

    public function __construct()
    {
        $this->pdo = Db::getInstance();
    }

    public function getTable()
    {
        return $this->table;
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->query($sql);
    }

    /**
     * Выбрать и вернуть все записи из таблицы 
     *
     * @return array
     */
    public function findAll()
    {
        $sql = "SELECT * FROM $this->table";
        return $this->pdo->query($sql);
    }

    /**
     * Выбрать одну запись
     *
     * @param  mixed $value Значение поля (по умолчанию id)
     * @param  string $field Имя поля
     *
     * @return array
     */
    public function findOne($value, $field = '')
    {
        $field = $field ?: $this->pKey;
        $sql = "SELECT * FROM {$this->table} WHERE $field = ? LIMIT 1";
        return $this->pdo->queryFetch($sql, [$value]);
    }

    /**
     * Выбрать записи по SQL-запросу
     *
     * @param  string $sql
     * @param  mixed $value
     *
     * @return array
     */
    public function findBySql($sql, $value = '')
    {
        return $this->pdo->query($sql, [$value]);
    }

    /**
     * Выбрать записи по конструкции LIKE
     *
     * @param  mixed $like
     * @param  string $field Поле по которому происходит выборка
     *
     * @return array
     */
    public function findLike($like, $field = '')
    {
        $field = $field ?: $this->pKey;
        $sql = "SELECT * FROM $this->table WHERE $field LIKE ?";
        try {
            return $this->pdo->query($sql, [$like]);
        } catch (PDOException $e) {
            echo 'Указанное поле не существут в базе: ' . '<span class="text-danger">' .$e->getMessage() . '</span>'
                . '<br>Ошибка произошла в файле: ' . $e->getFile() . ' на строке ' . $e->getLine();
        }
        
    }

    /**
     * Вставить новую запись в таблицу
     *
     * @param  array $data Ассоциативный массив структурированных данных, ключи и их порядок соответсвуют полям в таблице
     *
     * @return void
     */
    public function insert(array $data)
    {
        $fields = '';
        $values = [];
        foreach ($data as $field => $val) {
            $fields .= "$field,";
            $values[] = $val;
        }
        $fields = rtrim($fields, ',');

        $placeholders = '';
        for ($i = 0; $i < count($data); $i++) {
            $placeholders .= '?,';
        }
        $placeholders = rtrim($placeholders, ',');

        $sql = "INSERT INTO $this->table ($fields) VALUES ($placeholders)";

        $this->pdo->execute($sql, $values);
    }

    /**
     * Вставить новую запись в таблицу
     *
     * @param  array $data Ассоциативный массив неструктурированных данных, ключи соответсвуют полям в таблице, порядок ключей не важен
     *
     * @return void
     */
    public function insertSet(array $data)
    {
        $fields = '';

        foreach ($data as $field => $val) {
            $fields .= "$field=:$field,";
        }

        $fields = rtrim($fields, ',');
        $sql = "INSERT $this->table SET $fields";

        try {
            $this->pdo->execute($sql, $data);
        } catch (PDOException $e) {
            echo 'Ошибка вставки данных: ' . $e->getMessage();
        }
    }

    /**
     * Обновить запись/записи в таблице
     *
     * @param  array $data  Ассоциативный массив данных для обновления, 
     *                      имена ключей должны соответствовать именам полей в таблице, порядок не важен
     * @param  mixed $value Значение поля id (по умолчанию), по которому будет происходить поиск
     * @param  string $key  Имя поля, по которому будет происходить поиск, по умолчанию id
     * @param  string $sign Знак для условия обновления, по умолчанию =
     *
     * @return void
     */
    public function update(array $data, $value, $key = '', $sign = '=')
    {
        $key = $key ?: $this->pKey;
        $fields = '';
        foreach ($data as $field => $val) {
            $fields .= "$field=:$field,";
        }
        $fields = rtrim($fields, ',');
        $data['field'] = $value;
        
        $sql = "UPDATE $this->table SET $fields WHERE $key $sign :field";

        try {
            $this->pdo->execute($sql, $data);
        } catch (PDOException $e) {
            echo 'Ошибка обновления данных: ' . $e->getMessage();
        }
    }

    /**
     * Удаление записи/записей из таблицы
     *
     * @param  mixed $value
     * @param  string $key
     * @param  string $sign
     *
     * @return void
     */
    public function delete($value, $key = '', $sign = '=')
    {
        $key = $key ?: $this->pKey;
        $sql = "DELETE FROM $this->table WHERE $key $sign ?";
        $this->pdo->execute($sql, [$value]);
    }

    public function getAssoc($params = [])
    {
        $sql = "SELECT * FROM $this->table";
        $arr = $this->pdo->query($sql, $params);
        $arrCommon = [];
        foreach ($arr as $key => $value) {
            $val = [];
            foreach ($value as $k => $v) {
                if ($k != 'id') {
                    $val[$k] = $v;
                }
            }
            $arrCommon[$value['id']] = $val;
        }
        return $arrCommon;
    }
}