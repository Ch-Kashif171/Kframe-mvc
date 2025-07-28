<?php

namespace Core\Support\Traits\Builder;

trait Relational
{
    protected array $attributes = [];

    /**
     * @param $name
     * @return array|mixed|null
     */
    public function __get($name)
    {
        // If attribute exists, return it
        if (array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }
        // If a method exists with this name, treat as relationship
        if (method_exists($this, $name)) {
            $result = $this->$name();
            // If the result is a QueryBuilder, call get() and cache
            if ($result instanceof \Core\Database\QueryBuilder) {
                $related = $result->get();
                $this->attributes[$name] = $related;
                return $related;
            }
            // Otherwise, just return the result
            return $result;
        }
        return null;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return json_encode($this->attributes, JSON_PRETTY_PRINT);
    }

    /**
     * @param $method
     * @param $arguments
     * @return array
     * @throws \Exception
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            $result = $this->$method(...$arguments);
            // If the result is a QueryBuilder, call get() automatically
            if ($result instanceof \Core\Database\QueryBuilder) {
                return $result->get();
            }
            return $result;
        }
        throw new \Exception("Method {$method} does not exist on " . static::class);
    }

}