<?php
namespace Core;

use Core\Support\Session;

class Response
{
    public function __construct()
    {

    }

    public function with($type,$message){
         Session::put($type,$message);
        return $message;
    }

}