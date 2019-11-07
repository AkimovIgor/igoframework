<?php

namespace Vendor\Igoframework\Core\Base;

use Vendor\Igoframework\Core\Base\View;

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
}