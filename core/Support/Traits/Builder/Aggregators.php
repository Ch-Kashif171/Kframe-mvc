<?php

namespace Core\Support\Traits\Builder;

use Whoops\Exception\ErrorException;

trait Aggregators
{
    /**
     * @param $column
     * @param int|string $value
     * @return bool
     * @throws ErrorException
     */
    public function increment($column, int|string $value = 1): bool
    {
        return $this->doctrine->increment($column, $value);
    }

    /**
     * @param $column
     * @param int|string $value
     * @return bool
     * @throws ErrorException
     */
    public function decrement($column, int|string $value = 1): bool
    {
        return $this->doctrine->decrement($column, $value);
    }

    /**
     * @return bool
     * @throws ErrorException
     */
    public function exists(): bool
    {
        return $this->doctrine->exists();
    }

    /**
     * @param string $column
     * @return int
     */
    public function count(string $column = "*"): int
    {
        return $this->doctrine->count($column);
    }

    /**
     * @param $column
     * @return mixed
     */
    public function sum($column)
    {
        return $this->doctrine->sum($column);
    }

    /**
     * @param $column
     * @return mixed
     */
    public function max($column)
    {
        return $this->doctrine->max($column);
    }

    /**
     * @param $column
     * @return mixed
     */
    public function min($column)
    {
        return $this->doctrine->min($column);
    }

}