<?php

namespace Core\Controllers;

use Core\Facades\Traits\Middleware;

class BaseController
{
    use Middleware;

    public function model($model) {
        return model($model);
    }

}
