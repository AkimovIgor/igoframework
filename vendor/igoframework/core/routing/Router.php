<?php

namespace Igoframework\Core\Routing;

use Igoframework\Core\Exceptions\NotFoundException;

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

    /**
     * Возвращает данные ткущего маршрута
     *
     * @return array
     */
    public static function getCurrentRoute()
    {
        return self::$currentRoute;
    }

    /**
     * Ищет совпадение в таблице маршрутов с текущим маршрутом по регулярному выражению
     *
     * @param  string $url 
     *
     * @return boolean
     */
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
                if (! isset($route['prefix'])) {
                    $route['prefix'] = '';
                } else {
                    $route['prefix'] .= '\\';
                }
                self::$currentRoute = $route;
                return true;
            }
        }
        return false;
    }

    /**
     * Удаляем явные GET-параметры из адреснй строки
     *
     * @param  string $url 
     *
     * @return string
     */
    protected static function removeGetParams($url)
    {
        if ($url) {
            $params = explode('&', $url);
            if (! strpos($params[0], '=')) {
                return rtrim($params[0], '/');
            }
        }
        return '';
    }

    /**
     * Перенаправляет запрос на нужный маршрут
     *
     * @param  string $url 
     *
     * @return string
     */
    public static function dispatch($url)
    {
        $url = self::removeGetParams($url);
        
        if (self::matchRoutes($url)) {
            $controller = CONTROLLERS 
                . self::upper(self::$currentRoute['prefix'])
                . self::upper(self::$currentRoute['controller']) . 'Controller';
            if (class_exists($controller)) {
                $cObj = new $controller(self::$currentRoute);
                $action = self::lower(self::$currentRoute['action']) . 'Action';
                if (method_exists($cObj, $action)) {
                    $cObj->$action();
                    $cObj->getView();
                } else {
                    throw new NotFoundException("Экшен {$controller}::{$action} не найден.", 503);
                }
            } else {
                throw new NotFoundException("Контроллер {$controller} не найден.", 503);
            }
        } else {
            throw new NotFoundException("Страница не найдена", 404);
        }
    }

    /**
     * Переводит первые буквы в верхний регистр
     *
     * @param  string $str 
     *
     * @return string
     */
    public static function upper($str)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $str)));
    }

    /**
     * Переводит первую букву в нижний регистр
     *
     * @param  string $str 
     *
     * @return string
     */
    public static function lower($str)
    {
        return lcfirst(self::upper($str));
    }
}