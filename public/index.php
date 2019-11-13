<?php

$query = rtrim($_SERVER['QUERY_STRING'], '/');

header('Content-Type:text/html;charset=utf8');

define('DEBUG', true);
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
// require_once ROOT . '/config/autoload.php';
require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/config/route.php';
