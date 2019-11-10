<?php

namespace Vendor\Igoframework\Core;

class Registry
{
    private static $objects = [];
    private static $instance;

    private function __construct()
    {
        $config = require_once ROOT . '/config/components.php';
        foreach ($config['components'] as $name => $object) {
            self::$objects[$name] = new $object;
        }
    }

    public static function getInstance()
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        return self::$instance = new self;
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