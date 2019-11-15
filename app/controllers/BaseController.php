<?php

namespace App\Controllers;

use Igoframework\Core\Base\Controller;
use App\Models\Category;

class BaseController extends Controller
{
    protected $menu;

    public function __construct($route)
    {
        parent::__construct($route);
        $model = new Category();
        $this->menu = $model->findAll();
    }
}