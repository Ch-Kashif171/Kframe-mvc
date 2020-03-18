<?php

namespace Core\controller;
use Core\Facades\Traits\Middleware;

class BaseController
{
    use Middleware;

    public function model($model){
        return model($model);
    }

}
