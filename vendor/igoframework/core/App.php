<?php

namespace Vendor\Igoframework\Core;

use Vendor\Igoframework\Core\Exceptions\ExceptionsHandler;

class App
{
    public static $app;

    public function __construct()
    {
        self::$app = Registry::getInstance();
        new ExceptionsHandler();
    }
}