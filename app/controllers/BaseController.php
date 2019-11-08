<?php

namespace App\Controllers;

use Vendor\Igoframework\Core\Base\Controller;

class BaseController extends Controller
{
    protected $meta = [];

    public function setMeta($title = '', $description = '', $keywords = '')
    {
        $this->meta['title'] = $title;
        $this->meta['description'] = $description;
        $this->meta['keywords'] = $keywords;

        return $this->meta;
    }
}