<?php

use Vendor\Igoframework\Core\Router;

$query = rtrim($_SERVER['QUERY_STRING'], '/');

define('WWW', __DIR__);
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__) . '/app');
define('CORE', dirname(__DIR__) . '/vendor/igoframework/core');
define('LIBS', dirname(__DIR__) . '/vendor/igoframework/core/libs');
define('LAYOUT', 'default');

define('CONTROLLERS', 'app\controllers\\');
define('MODELS', 'app\models\\');
define('VIEWS', 'app\views\\');

require_once LIBS . '/functions.php';

spl_autoload_register(function($className) {
    $className = str_replace('\\', '/', $className);
    $file = ROOT . "/{$className}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

Router::add('^$', ['controller' => 'main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

Router::dispatch($query);