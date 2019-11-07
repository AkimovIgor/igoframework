<?php

namespace App\Controllers;

class MainController extends BaseController
{
    public function indexAction()
    {
        $name = 'Igor';
        $this->setVars(compact('name'));
    }
}