<?php

namespace App\Controllers;

use Vendor\Igoframework\Core\Base\Controller;
use App\Models\Category;

class BaseController extends Controller
{
    protected $meta = [];
    protected $menu;

    public function __construct($route)
    {
        parent::__construct($route);
        $model = new Category();
        $this->menu = $model->findAll();
    }

    public function setMeta($title = '', $description = '', $keywords = '')
    {
        $this->meta['title'] = $title;
        $this->meta['description'] = $description;
        $this->meta['keywords'] = $keywords;

        return $this->meta;
    }
}