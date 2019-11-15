<?php

namespace Igoframework\Core\Base;

use Igoframework\Core\Base\View;

abstract class Controller
{
    protected $route = [];    // текущий маршрут
    protected $layout;        // шаблон
    protected $view;          // вид (представление)
    protected $vars;          // пользовательские переменные

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
    }

    /**
     * Устанавливает массив пользовательских переменных для передачи их в вид
     *
     * @param  array $vars Массив пользовательских переменных
     *
     * @return void
     */
    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    /**
     * Получает нужный вид и отображает его вместе с массивом
     * пользовательских переменных
     *
     * @return void
     */
    public function getView()
    {
        $vObj = new View($this->route, $this->layout, $this->view);
        $vObj->render($this->vars);
    }

    /**
     * Проверяет, был ли отправлен AJAX запрос
     *
     * @return boolean
     */
    public function isAjax()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
    }

    /**
     * Загружает текущий вид при AJAX запросе
     *
     * @param  string $view Нужный вид
     * @param  array $vars Массив пользовательских переменных
     *
     * @return void
     */
    public function loadView($view, $vars = [])
    {
        if (!empty($vars)) {
            extract($vars);
        }
        require_once APP . "/views/{$this->route['controller']}/$view.php";
    }
}