<?php
namespace Core\Database;

use Core\Support\Traits\Builder\Builder;
use Core\Support\Traits\Builder\StaticForwarding;

/**
 * Base ORM Model
 *
 * @property int $id
 */
class BaseModel
{
    use Builder, StaticForwarding, OrmMethods;

    protected array $attributes = [];

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function __toString()
    {
        return json_encode($this->attributes, JSON_PRETTY_PRINT);
    }
}