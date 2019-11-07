<?php

namespace Vendor\Igoframework\Core;

class Router
{
    public static $tableRoutes = [];
    public static $currentRoute = [];

    public static function add($regExp, $route = [])
    {
        self::$tableRoutes[$regExp] = $route;
    }

    public static function getTableRoutes()
    {
        return self::$tableRoutes;
    }

    public static function getCurrentRoute()
    {
        return self::$currentRoute;
    }

    private static function matchRoutes($url)
    {
        foreach (self::$tableRoutes as $pattern => $route) {
            if (preg_match("#$pattern#i", $url, $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $route[$key] = $value;
                    }
                }
                if (! isset($route['action'])) {
                    $route['action'] = 'index';
                }
                self::$currentRoute = $route;
                return true;
            }
        }
        return false;
    }

    public static function dispatch($url)
    {
        if (self::matchRoutes($url)) {
            $controller = CONTROLLERS . self::upper(self::$currentRoute['controller']) . 'Controller';
            if (class_exists($controller)) {
                $cObj = new $controller(self::$currentRoute);
                $action = self::lower(self::$currentRoute['action']) . 'Action';
                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                    $cObj->getView();
                } else {
                    echo 'Action not found';
                }
            } else {
                echo 'Controller not found';
            }
        } else {
            echo '404';
        }
    }

    public static function upper($str)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    public static function lower($str)
    {
        return lcfirst(self::upper($str));
    }
}