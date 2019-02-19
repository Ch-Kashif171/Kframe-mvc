<?php

namespace Core\Facades;
use Closure;

class Request
{

    public function post($key){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST[$key];
        } else {
            return "You have provide get method";
        }


    }

    public function get($key){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $_GET[$key];
        } else {
            return "You have provide post method";
        }
    }

    public function all(){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $_GET;
        } else {
            return $_POST;
        }
    }

    public function except(){

        $args = func_get_args();
        $inputs = $_POST;
        foreach ($args as $value){
            unset($inputs[$value]);
        }
        return $inputs;
    }

    public function only(){

        $args = func_get_args();
        $inputs = $_POST;
        $values = [];
        foreach ($args as $value){
            $values[$value] = $inputs[$value];
        }
        return $values;
    }

    public function session()
    {
        return new Session();
    }

}