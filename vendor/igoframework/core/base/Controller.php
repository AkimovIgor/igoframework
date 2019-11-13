<?php

namespace Igoframework\Core\Base;

use Igoframework\Core\Base\View;

abstract class Controller
{
    protected $route = [];
    protected $layout;
    protected $view;
    protected $vars;

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
    }

    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    public function getView()
    {
        $vObj = new View($this->route, $this->layout, $this->view);
        $vObj->render($this->vars);
    }

    public function isAjax()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' );
    }

    public function loadView($view, $vars = [])
    {
        if (!empty($vars)) {
            extract($vars);
        }
        require_once APP . "/views/{$this->route['controller']}/$view.php";
    }
}