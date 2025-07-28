<?php

namespace Core\Support\Traits\Builder;

trait Getters
{
    public function all(): array
    {
        return $this->get();
    }

    public function get(): array
    {
        $result = $this->doctrine->get();
        $result = self::getResult($result);
        if ($this->modelClass && class_exists($this->modelClass)) {
            return $this->modelClass::hydrateMany($result);
        }
        return $result;
    }

    public function first()
    {
        $result = $this->doctrine->first();
        $result = self::getResult($result);
        if ($this->modelClass && class_exists($this->modelClass)) {
            return $this->modelClass::hydrate($result);
        }
        return $result;
    }

    public function pluck($columns): array
    {
        return $this->doctrine->pluck($columns);
    }

    public function find($id)
    {
        $result = $this->doctrine->find($id);
        $result = self::getResult($result);
        if ($this->modelClass && class_exists($this->modelClass)) {
            return $this->modelClass::hydrate($result);
        }
        return $result;
    }

    public function firstOrFail()
    {
        $result = $this->doctrine->firstOrFail();
        $result = self::getResult($result);
        if ($this->modelClass && class_exists($this->modelClass)) {
            return $this->modelClass::hydrate($result);
        }
        return $result;
    }

    public function paginate($limit): array
    {
        $result = $this->doctrine->paginate($limit);
        $result = self::getResult($result);
        if ($this->modelClass && class_exists($this->modelClass)) {
            return $this->modelClass::hydrateMany($result);
        }
        return $result;
    }

    public function simplePaginate($limit): array
    {
        $result = $this->doctrine->simplePaginate($limit);
        $result = self::getResult($result);
        if ($this->modelClass && class_exists($this->modelClass)) {
            return $this->modelClass::hydrateMany($result);
        }
        return $result;
    }

}