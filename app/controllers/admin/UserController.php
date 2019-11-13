<?php

namespace App\Controllers\Admin;

use Igoframework\Core\Base\View;

class UserController extends BaseController
{
    public function indexAction()
    {
        View::setMeta('User', 'descript', 'keywords');
    }

    public function testAction()
    {
        View::setMeta('Test', 'descript', 'keywords');
    }
}