<?php

namespace Vendor\Igoframework\Core\Base;

use Vendor\Igoframework\Core\Exceptions\NotFoundException;

class View
{
    protected $route = [];
    protected $layout;
    protected $view;
    public $scripts = [];

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
            throw new NotFoundException("Вид {$fileView} не найден", 404);
        }

        $content = ob_get_clean();

        if ($this->layout !== false) {
            $fileLayout = APP . "/views/layouts/$this->layout.php";
            if (is_file($fileLayout)) {
                $content = $this->getScripts($content);
                $scripts = [];
                if (!empty($this->scripts[0])) {
                    $scripts = $this->scripts[0];
                }
                require_once $fileLayout;
            } else {
                echo 'Layout not found';
            }
        }
    }

    public function getScripts($content)
    {
        $pattern = "#<script.*?>.*?</script>#si";

        preg_match_all($pattern, $content, $this->scripts);
        
        if (!empty($this->scripts)) {
            $content = preg_replace($pattern, '', $content);
        }
        return $content;
    }
}