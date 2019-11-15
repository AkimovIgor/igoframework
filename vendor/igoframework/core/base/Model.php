<?php

namespace Igoframework\Core\Base;

use Igoframework\Core\Database\Db;
use Valitron\Validator;

abstract class Model extends Db
{
    protected $pdo;             // объект PDO
    protected $table;           // текущая таблица
    protected $pKey = 'id';     // первичный ключ для поиска по умалчанию
    public $attributes = [];    // массив атрибутов 
    protected $errors = [];     // массив ошибок
    protected $rules = [];      // массив правил валидации

    public function __construct()
    {
        $this->pdo = Db::getInstance();
    }

    /**
     * Получает название таблицы
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Загрузка данных в существующие атрибуты
     *
     * @param  array $data Массив данных пришедших запросом POST|GET
     *
     * @return void
     */
    public function load($data) {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = hsc($data[$name]);
            }
        }
    }

    /**
     * Сохранение данных в таблицу и возврат состояния
     *
     * @param  array $data Массив данных пришедших запросом POST|GET
     *
     * @return boolean
     */
    public function save($data)
    {
        return $this->insertSet($data) ? true : false;
    }

    /**
     * Получить список ошибок для вывода в html и записать их в сессию
     *
     * @return void
     */
    public function getErrors()
    {
        $errList = '<ul class="mb-0">';
        // dd($this->errors, 0);
        foreach ($this->errors as $errors) {
            foreach ($errors as $error) {
                $errList .= '<li>' . $error . '</li>';
            }
        }
        $errList .= '</ul>';
        $_SESSION['errors'] = $errList;
    }

    /**
     * Валидация данных, пришедших запросом POST|GET
     *
     * @param  array $data Массив данных пришедших запросом POST|GET
     *
     * @return boolean
     */
    public function validate($data)
    {
        Validator::lang('ru');
        $v = new Validator($data);
        $v->rules($this->rules);
        if ($v->validate()) return true;
        $this->errors = $v->errors();
        return false;
    }

    /**
     * Выполнить подготовленный sql-запрос с параметрами и вернуть данные
     *
     * @param  string $sql SQL-запрос
     * @param  array $params Массив подготовленных параметров
     *
     * @return array
     */
    public function query($sql, $params = [])
    {
        $stmt = $this->pdo->query($sql, $params);
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
     * Выброр одной записи по условию которое строится из массива полей,
     * также нужно указать оператор для полей если их больше 1
     *
     * @param  array $fields Массив полей со значениями
     * @param  string $operator Оператор между несколькими полями, например AND
     *
     * @return void
     */
    public function findOneWhere(array $fields, $operator = '')
    {
        $fieldsData = '';
        foreach ($fields as $field => $val) {
            $fieldsData .= "$field=:$field";
            if ($operator) $fieldsData .= " $operator ";
        }
        $fieldsData = rtrim($fieldsData, " {$operator} ");
        $sql = "SELECT * FROM {$this->table} WHERE $fieldsData LIMIT 1";
        return $this->pdo->queryFetch($sql, $fields);
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
        } catch (NotFoundException $e) {
            throw new NotFoundException('Ошибка выборки: ' . $e->getMessage());
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
        for ($i = 0; $i < count($data); $i++) $placeholders .= '?,';
        $placeholders = rtrim($placeholders, ',');
        $sql = "INSERT INTO $this->table ($fields) VALUES ($placeholders)";
        $this->pdo->execute($sql, $values);
    }

    /**
     * Вставить новую запись в таблицу
     *
     * @param  array $data Ассоциативный массив неструктурированных данных, 
     *                     ключи соответсвуют полям в таблице, порядок ключей не важен
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
            return true;
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
     * @param  mixed $value Значение ключа(поля) для поиска записи, по умолчанию - id
     * @param  string $key Ключ(поле) для поиска записи
     * @param  string $sign Знак(оператор) между ключом и значением, по умолчинию =
     *
     * @return void
     */
    public function delete($value, $key = '', $sign = '=')
    {
        $key = $key ?: $this->pKey;
        $sql = "DELETE FROM $this->table WHERE $key $sign ?";
        $this->pdo->execute($sql, [$value]);
    }

    /**
     * Получает все записи из БД, где ключ - id записи,
     * а значение - массив данных других полей кроме id
     *
     * @param  array $params Массив подготовленных данных
     *
     * @return array
     */
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