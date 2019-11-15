<?php

// автозагрузка классов
spl_autoload_register(function($className) {
    $className = str_replace('\\', '/', $className);
    $file = ROOT . "/{$className}.php";
    if (file_exists($file)) {
        require_once $file;
    }
});