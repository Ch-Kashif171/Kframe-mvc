<?php

namespace Core\Support\Traits;

use Core\Support\ModelFactory;

trait Modelable
{
    public function model($model)
    {
        return ModelFactory::make($model);
    }
}