<?php

$driver = 'mysql';
$hostName = 'localhost';
$dbName = 'myselfoop';
$userName = 'root';
$password = '';
$charset = 'utf8';
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