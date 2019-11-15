<?php

namespace Igoframework\Core\Traits;

trait TSingletone
{
    private static $instance;

    public static function getInstance()
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        return self::$instance = new self;
    }

    private function __clone() {}

    private function __wakeup() {}

    private function __sleep() {}
}