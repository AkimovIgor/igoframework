<?php

namespace Igoframework\Core;

use Igoframework\Core\Traits\TSingletone;

class Registry
{
    use TSingletone;

    private static $objects = [];

    private function __construct()
    {
        $config = require_once ROOT . '/config/components.php';
        foreach ($config['components'] as $name => $object) {
            self::$objects[$name] = new $object;
        }
    }

    public static function get($name)
    {
        if (is_object(self::$objects[$name])) {
            return self::$objects[$name];
        }
    }

    public static function set($name, $object)
    {
        if (! isset(self::$objects[$name])) {
            self::$objects[$name] = new $object;
        }
    }

    public function __get($name)
    {
        return self::get($name);
    }

    public function __set($name, $object)
    {
        self::set($name, $object);
    }

    public function getList()
    {
        return self::$objects;
    }
}