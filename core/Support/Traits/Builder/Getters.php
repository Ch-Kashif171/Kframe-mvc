<?php

namespace Core\Support\Traits\Builder;

use Core\Support\Collection;

trait Getters
{
    use Wrapper;

    public function all(): array|Collection
    {
        return $this->wrapMultiple(fn() => $this->doctrine->get());
    }

    public function get(): array|Collection
    {
        return $this->wrapMultiple(fn() => $this->doctrine->get());
    }

    public function first()
    {
        return $this->wrapSingle(fn() => $this->doctrine->first());
    }

    public function pluck($columns): array
    {
        return $this->doctrine->pluck($columns);
    }

    public function find($id)
    {
        return $this->wrapSingle(fn() => $this->doctrine->find($id));
    }

    public function firstOrFail()
    {
        return $this->wrapSingle(fn() => $this->doctrine->firstOrFail());
    }

    public function paginate($limit)
    {
        return $this->doctrine->paginate($limit);
    }

    public function simplePaginate($limit): array
    {
        return $this->doctrine->simplePaginate($limit);
    }

}