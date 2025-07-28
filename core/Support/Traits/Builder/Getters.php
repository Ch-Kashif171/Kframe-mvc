<?php

namespace Core\Support\Traits\Builder;

trait Getters
{
    use Hydrate, Wrapper;

    public function all(): array
    {
        return $this->wrapMultiple(fn() => $this->doctrine->get());
    }

    public function get(): array
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

    public function paginate($limit): array
    {
        return $this->wrapMultiple(fn() => $this->doctrine->paginate($limit));
    }

    public function simplePaginate($limit): array
    {
        return $this->wrapMultiple(fn() => $this->doctrine->simplePaginate($limit));
    }

}