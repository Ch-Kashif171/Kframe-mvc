<?php

namespace Core\Support\Traits\Builder;

trait Arrayable
{
    /**
     * @return array|mixed
     */
    public function toArray()
    {
        return $this->recursiveToArray($this->attributes);
    }

    /**
     * @param $value
     * @return mixed
     */
    private function recursiveToArray($value): mixed
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

}