<?php

namespace Igoframework\Core;

use Igoframework\Core\Exceptions\ExceptionsHandler;

class App
{
    public static $app;

    public function __construct()
    {
        session_start();
        self::$app = Registry::getInstance();
        new ExceptionsHandler();
    }
}