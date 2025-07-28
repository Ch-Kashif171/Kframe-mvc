<?php

namespace Core\Support\Traits\Builder;

trait Wrapper
{
    /**
     * @param callable $callback
     * @return array
     */
    protected function wrapMultiple(callable $callback): array
    {
        // Execute the query and retrieve raw results from the database
        $result = $callback();

        // Filter out hidden attributes as defined in the model's $hidden property
        $result = self::getResult($result);

        // Automatically load any defined relationships (eager loading)
        return self::hydrates($result);
    }

    /**
     * @param callable $callback
     * @return mixed
     */
    protected function wrapSingle(callable $callback)
    {
        // Execute the query and retrieve raw results from the database
        $result = $callback();

        // Filter out hidden attributes as defined in the model's $hidden property
        $result = self::getResult($result);

        // Automatically load any defined relationships (eager loading)
        return self::hydrate($result);
    }

}