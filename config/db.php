<?php

// Настройка соединения с БД

$driver = 'mysql';            // драйвер
$hostName = 'localhost';      // имя хоста
$dbName = 'myselfoop';        // имя базы данных
$userName = 'root';           // имя пользователя
$password = null;             // пароль
$charset = 'utf8';            // кодировка
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

return [
    'dsn' => "$driver:host=$hostName;dbname=$dbName;charset=$charset",
    'user' => $userName,
    'password' => $password,
    'options' => $options
];