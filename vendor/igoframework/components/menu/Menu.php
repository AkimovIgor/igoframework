<?php

namespace Igoframework\Components\Menu;

use Igoframework\Core\Cache\CacheManager;

class Menu
{
    protected $data;                         // массив данных
    protected $htmlTree;                     // массив для html дерева
    protected $menuHtml;                     // сформиированное html меню
    protected $tmpPath;                      // путь к текущему шаблону
    protected $styles;                       // массив стилей
    protected $tmpName = 'default_menu';     // имя текущего шаблонаы
    protected $class = 'menu';               // класс меню
    protected $container = 'ul';             // контейнер для меню
    protected $table = 'category';           // таблица БД для получения данных
    protected $cache = false;                // статус кэширования меню
    protected $cacheKey = 'cache_menu';      // имя кэша
    protected $cacheTime = 3600;             // время кэширования

    public function __construct($options = [])
    {
        $this->getOptions($options);
        $this->tmpPath = __DIR__ . "/menu_tpl/{$this->tmpName}.php";
        $this->run();
        $this->outputHtml();
    }

    /**
     * Запуск всех методов
     *
     * @return void
     */
    protected function run()
    {
        $modelName = '\App\Models\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $this->table)));
        $model = new $modelName;
        $this->data = $model->getAssoc();
        $this->htmlTree = $this->getTree();
        if ($this->cache) {
            $cache = new CacheManager();
            if ($cache->getCache($this->cacheKey)) {
                $this->menuHtml = $cache->getCache($this->cacheKey);
            } else {
                $this->menuHtml = $this->getMenuHtml($this->htmlTree);
                $cache->setCache($this->cacheKey, $this->menuHtml, $this->cacheTime);
            }
        }
        $this->menuHtml = $this->getMenuHtml($this->htmlTree);
    }

    /**
     * Вывод Html кода на экран
     *
     * @return void
     */
    protected function outputHtml()
    {
        $styles = '';
        if (!empty($this->styles)) {
            foreach ($this->styles as $prop => $value) {
                $styles .= $prop . ': ' . $value . ';';
            }
            $styles = " style='{$styles}'";
        }
        echo "<{$this->container} class='{$this->class}'{$styles}>";
        echo $this->menuHtml;
        echo "</{$this->container}>";
    }

    /**
     * Получить список опций для меню 
     * и установить значение опции, если она существует
     *
     * @param  array $options Массив опций
     *
     * @return void
     */
    protected function getOptions(array $options)
    {
        if (!empty($options)) {
            foreach ($options as $prop => $value) {
                if (property_exists($this, $prop)) {
                    $this->$prop = $value;
                }
            }
        }
    }

    /**
     * Формирование Html дерева
     *
     * @return array
     */
    protected function getTree()
    {
        $tree = [];
        $data = $this->data;
        foreach ($data as $id => &$node) {
            if (!$node['parent_id']) {
                $tree[$id] = &$node;
            } else {
                $data[$node['parent_id']]['childs'][$id] = &$node;
            }
        }
        return $tree;
    }

    /**
     * Получает готовый Html из дерева элементов
     *
     * @param  array $tree Массив для html дерева
     * @param  boolean $dropdown Статус выпадающего списка
     * @param  string $tab Табуляция для отображения уровня вложенности в списках
     *
     * @return string
     */
    protected function getMenuHtml($tree, $dropdown = false, $tab = '')
    {
        $str = '';
        foreach ($tree as $id => $category) {
            $str .= $this->catToTemplate($category, $tab, $id, $dropdown);
        }
        return $str;
    }

    /**
     * Буферизация и подключение шаблона
     *
     * @param  array $category Массив текущей категории
     * @param  string $tab Табуляция для отображения уровня вложенности в списках
     * @param  int $id Идентификатор текущей категории
     * @param  boolean $dropdown Статус выпадающего списка
     *
     * @return string
     */
    protected function catToTemplate($category, $tab, $id, $dropdown)
    {
        ob_start();
        require $this->tmpPath;
        return ob_get_clean();
    }
}