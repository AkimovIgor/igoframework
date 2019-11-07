<?php

namespace Vendor\Igoframework\Core\Base;

class View
{
    protected $route = [];
    protected $layout;
    protected $view;

    public function __construct($route, $layout = '', $view = '')
    {
        $this->route = $route;
        if ($layout !== false) {
            $this->layout = $layout ?: LAYOUT;
        } else {
            $this->layout = false;
        }
        $this->view = $view;
    }

    public function render($vars)
    {
        if (is_array($vars)) {
            extract($vars);
        }

        ob_start();

        $fileView = APP . "/views/{$this->route['controller']}/$this->view.php";
        if (is_file($fileView)) {
            require_once $fileView;
        } else {
            echo 'View not found';
        }

        $content = ob_get_clean();

        if ($this->layout !== false) {
            $fileLayout = APP . "/views/layouts/$this->layout.php";
            if (is_file($fileLayout)) {
                require_once $fileLayout;
            } else {
                echo 'Layout not found';
            }
        }
    }
}