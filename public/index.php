<?php

use Vendor\Igoframework\Core\Routing\Router;
use Vendor\Igoframework\Core\App;

$query = rtrim($_SERVER['QUERY_STRING'], '/');

header('Content-Type:text/html;charset=utf8');

define('DEBUG', 0);
define('WWW', __DIR__);
define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__) . '/app');
define('CORE', dirname(__DIR__) . '/vendor/igoframework/core');
define('LIBS', dirname(__DIR__) . '/vendor/igoframework/core/libs');
define('LAYOUT', 'default');

define('CONTROLLERS', 'App\Controllers\\');
define('MODELS', 'App\Models\\');
define('VIEWS', 'App\Views\\');

require_once LIBS . '/functions.php';



spl_autoload_register(function($className) {
    $className = str_replace('\\', '/', $className);
    $file = ROOT . "/{$className}.php";
    if (is_file($file)) {
        require_once $file;
    }
});

$app = new App();

Router::add('^$', ['controller' => 'main', 'action' => 'index']);
Router::add('^(?P<controller>[a-z-]+)/?(?P<action>[a-z-]+)?$');

Router::dispatch($query);