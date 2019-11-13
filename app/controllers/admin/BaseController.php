<?php

namespace App\Controllers\Admin;

use Igoframework\Core\Base\Controller;

class BaseController extends Controller
{
    protected $layout = 'admin';

    public function __construct($route)
    {
        parent::__construct($route);
    }
}