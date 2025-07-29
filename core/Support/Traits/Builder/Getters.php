<?php

namespace Core\Support\Traits\Builder;

use Core\Support\Collection\Collection;

trait Getters
{
    use Wrapper;

    /**
     * @return array|\Core\Support\Collection\Collection
     * @throws \Whoops\Exception\ErrorException
     */
    public function all(): array|Collection
    {
        return $this->wrapMultiple(fn() => $this->doctrine->get());
    }

    /**
     * @return array|\Core\Support\Collection\Collection
     * @throws \Whoops\Exception\ErrorException
     */
    public function get(): array|Collection
    {
        return $this->wrapMultiple(fn() => $this->doctrine->get());
    }

    /**
     * @return mixed
     * @throws \Whoops\Exception\ErrorException
     */
    public function first()
    {
        return $this->wrapSingle(fn() => $this->doctrine->first());
    }

    /**
     * @param $columns
     * @return array
     */
    public function pluck($columns): array
    {
        return $this->doctrine->pluck($columns);
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Whoops\Exception\ErrorException
     */
    public function find($id)
    {
        return $this->wrapSingle(fn() => $this->doctrine->find($id));
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function firstOrFail()
    {
        return $this->wrapSingle(fn() => $this->doctrine->firstOrFail());
    }

    /**
     * @param $limit
     * @return mixed
     */
    public function paginate($limit)
    {
        return $this->wrapPaginate(fn() => $this->doctrine->paginate($limit));
    }

    /**
     * @param $limit
     * @return array
     */
    public function simplePaginate($limit): array
    {
        return $this->wrapSimplePaginate(fn() => $this->doctrine->simplePaginate($limit));
    }

}