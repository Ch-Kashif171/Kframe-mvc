<?php

namespace Core\Support\Facades;

abstract class Facade
{
    protected static array $resolvedInstances = [];

    /**
     * @return mixed
     */
    protected static function getFacadeRoot(): mixed
    {
        $accessor = static::getFacadeAccessor();

        if (!isset(static::$resolvedInstances[$accessor])) {
            static::$resolvedInstances[$accessor] = app($accessor);
        }

        return static::$resolvedInstances[$accessor];
    }

    /**
     * @param $method
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        $instance = static::getFacadeRoot();

        if (!$instance) {
            throw new \RuntimeException('Facade root not found.');
        }

        return $instance->$method(...$arguments);
    }

    abstract protected static function getFacadeAccessor();
}
