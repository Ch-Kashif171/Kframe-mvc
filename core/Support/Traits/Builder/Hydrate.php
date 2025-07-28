<?php

namespace Core\Support\Traits\Builder;

trait Hydrate
{

    public function hydrates($result)
    {
        if ($this->modelClass && class_exists($this->modelClass)) {
            return $this->modelClass::hydrateMany($result);
        }

        return $result;
    }

    public function hydrate($result)
    {
        if ($this->modelClass && class_exists($this->modelClass)) {
            return $this->modelClass::hydrate($result);
        }

        return $result;
    }
}