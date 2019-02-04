<?php

namespace Core\controller;


class BaseController
{

    public function model($model){
        return load($model);
    }

}