<?php

use Core\Doctrine;

class Collection
{
    public $result;
    public function __construct()
    {
        debug($this->result);
    }

    protected function getArrayAbleItems($items)
    {
        return (array) $items;
    }

    public function toArray(){
        return $this->result;
    }

}