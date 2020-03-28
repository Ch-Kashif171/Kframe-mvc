<?php

namespace Core\Facades;

class Request
{
    private $field = array();


    public function post($key){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $_POST[$key];
        } else {
            return "You have provided get method";
        }


    }

    public function get($key){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return $_GET[$key];
        } else {
            return "You have provided post method";
        }
    }

    public function all(){

        $file = [];
        $fields = [];
        if (isset($_FILES)) {
            $file = $this->getFilesName();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $fields = $_GET;
        } else {
            $fields = $_POST;
        }

        return array_merge($fields,$file);
    }

    public function getFile($file_name) {
        if (isset($_FILES)) {
            return $_FILES[$file_name];
        } else {
            return false;
        }
    }

    public function getFiles() {

        if (isset($_FILES)) {
            return $_FILES;
        } else {
            return false;
        }
    }

    public function hasFile($key) {

        if (isset($_FILES[$key]) && $_FILES[$key]['name'] != '') {
            return true;
        } else {
            return false;
        }
    }

    public function has($key) {

        $field = false;

        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !isset($_FILES[$key])) {
            if (isset($_GET[$key])) {
                $field = true;
            } else {
                $field = false;
            }
        } else if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_FILES[$key])) {
            if (isset($_POST[$key])) {
                $field = true;
            } else {
                $field = false;
            }
        } else if (isset($_FILES[$key])) {
            $field = true;
        } else {
            $field = false;
        }

        return $field;
    }

    private function getFilesName() {

        $names = [];
        $files = $_FILES;
        foreach ($files as $name => $file) {
            $names[$name] = $file['name'];
        }

        return $names;
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

    public function __get($name)
    {
        $this->field = $this->all();
        if(isset($this->field[$name])) {
            return $this->field[$name];
        } else {
            throw new \Exception("$name does not exists");
        }
    }
}