<?php

namespace App\Controllers;

use App\Models\Main;

class MainController extends BaseController
{
    public function indexAction()
    {
        $model = new Main();

        $posts = $model->findAll();

        $meta = $this->setMeta('Главная', 'главная страница');
        $this->setVars(compact('posts', 'meta'));
    }
}