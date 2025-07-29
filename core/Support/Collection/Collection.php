<?php

namespace Core\Support\Collection;

class Collection implements \ArrayAccess, \IteratorAggregate, \Countable
{
    protected array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function toArray(): array
    {
        return array_map(function ($item) {
            if (is_object($item) && method_exists($item, 'toArray')) {
                return $item->toArray();
            } elseif (is_array($item)) {
                return $this->recursiveToArray($item);
            }
            return $item;
        }, $this->items);
    }

    private function recursiveToArray($value)
    {
        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        } elseif (is_array($value)) {
            $result = [];
            foreach ($value as $k => $v) {
                $result[$k] = $this->recursiveToArray($v);
            }
            return $result;
        }
        return $value;
    }

    // ArrayAccess
    public function offsetExists($offset): bool { return isset($this->items[$offset]); }
    public function offsetGet($offset): mixed { return $this->items[$offset]; }
    public function offsetSet($offset, $value): void { $this->items[$offset] = $value; }
    public function offsetUnset($offset): void { unset($this->items[$offset]); }

    // IteratorAggregate
    public function getIterator(): \Traversable { return new \ArrayIterator($this->items); }

    // Countable
    public function count(): int { return count($this->items); }

    // For convenience
    public function all(): array { return $this->items; }
} 