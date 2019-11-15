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

    /**
     * Подключить нужный шаблон и вид предварительно передав пользовательские переменные
     *
     * @param  array $vars Массив пользовательских переменных
     *
     * @return void
     */
    public function render($vars)
    {
        if (is_array($vars)) {
            extract($vars);
        }
        ob_start();
        $fileView = APP . "/views/{$this->route['prefix']}{$this->route['controller']}/$this->view.php";
        if (file_exists($fileView)) {
            require_once $fileView;
        } else {
            throw new NotFoundException("Вид {$fileView} не найден", 404);
        }
        $content = ob_get_clean();
        if ($this->layout !== false) {
            $fileLayout = APP . "/views/layouts/$this->layout.php";
            if (file_exists($fileLayout)) {
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

    /**
     * Вырезать скрипты на странице и переместить их в массив скриптов,
     * чтобы вставить в любое другое место на странице
     *
     * @param  string $content Контент страницы
     *
     * @return string
     */
    public function getScripts($content)
    {
        $pattern = "#<script.*?>.*?</script>#si";
        preg_match_all($pattern, $content, $this->scripts);
        if (!empty($this->scripts)) {
            $content = preg_replace($pattern, '', $content);
        }
        return $content;
    }

    /**
     * Установить мета-данные
     *
     * @param  string $title Заголовок страницы
     * @param  string $description Описание страницы
     * @param  string $keywords Ключевые слова
     *
     * @return void
     */
    public static function setMeta($title = '', $description = '', $keywords = '')
    {
        self::$meta['title'] = $title;
        self::$meta['description'] = $description;
        self::$meta['keywords'] = $keywords;
    }

    /**
     * Получить метаданные для отображения в html
     *
     * @return void
     */
    public static function getMeta()
    {
        echo '<title>' . self::$meta['title'] . '</title>';
        echo '<meta name="description" content="' . self::$meta['description'] . '">';
        echo '<meta name="keywords" content="' . self::$meta['keywords'] . '">';
    }
}