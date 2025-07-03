<?php

namespace Core\Controllers;

use Core\Support\Traits\Middleware;

class BaseController
{
    use Middleware;

    public function model($model) {
        return model($model);
    }

}
