<?php

namespace Igoframework\Core\Base;

use Igoframework\Core\Exceptions\NotFoundException;

class View
{
    protected $route = [];        // текущий маршрут
    protected $layout;            // текущий шаблон
    protected $view;              // текущий вид
    public $scripts = [];         // массив скриптов
    protected static $meta = [    // массив мета-данных
        'title' => '',
        'description' => '',
        'keywords' => '',
    ];

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

        $fileView = APP . "/views/{$this->route['prefix']}{$this->route['controller']}/$this->view.php";
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
                throw new NotFoundException("Шаблон {$fileLayout} не найден", 404);
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

    public static function setMeta($title = '', $description = '', $keywords = '')
    {
        self::$meta['title'] = $title;
        self::$meta['description'] = $description;
        self::$meta['keywords'] = $keywords;
    }

    public static function getMeta()
    {
        echo '<title>' . self::$meta['title'] . '</title>';
        echo '<meta name="description" content="' . self::$meta['description'] . '">';
        echo '<meta name="keywords" content="' . self::$meta['keywords'] . '">';
    }
}