<?php

namespace App\Controllers;

use App\Models\Main;
use Igoframework\Core\App;
use Igoframework\Core\Base\View;

class MainController extends BaseController
{
    public function indexAction()
    {
        $model = new Main();

        $posts = $model->findAll();
        $menu = $this->menu;
        View::setMeta('Главная');
        $this->setVars(compact('menu', 'posts', 'meta'));
    }

    public function testAction()
    {
        $model = new Main;
        
        if ($this->isAjax()) {
            $post = $model->findOne($_POST['id']);
            $this->loadView('test', compact('post'));
            die;
        }
        $menu = $this->menu;
        View::setMeta('Test');
        $this->setVars(compact('menu', 'post', 'meta'));
    }
}