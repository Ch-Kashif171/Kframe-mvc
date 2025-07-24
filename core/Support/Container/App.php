<?php

namespace Core\Support\Container;

class App
{
    protected array $bindings = [];
    protected array $instances = [];

    /**
     * Bind a class or closure to the container.
     */
    public function bind(string $key, mixed $concrete)
    {
        $this->bindings[$key] = $concrete;
    }

    /**
     * Bind a singleton instance.
     */
    public function singleton(string $key, mixed $instance)
    {
        $this->instances[$key] = $instance;
    }

    /**
     * Resolve an instance from the container.
     */
    public function make(string $key): mixed
    {
        // If already a singleton
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        // If binding is a closure or concrete class
        if (isset($this->bindings[$key])) {
            $concrete = $this->bindings[$key];

            // If it's a closure, call it
            if ($concrete instanceof \Closure) {
                return $concrete($this);
            }

            // If it's a class name, instantiate
            if (is_string($concrete) && class_exists($concrete)) {
                return new $concrete;
            }

            return $concrete; // raw object
        }

        throw new \RuntimeException("Nothing bound in the container for key [$key]");
    }
}
