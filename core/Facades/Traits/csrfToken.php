<?php

namespace Core\Facades\Traits;
use Core\Facades\Session;

trait csrfToken
{
    public static function check(){

        if (isset($_POST['csrf_token']) && Session::has('csrf_token')){
            if (Session::get('csrf_token') === $_POST['csrf_token']){ // this is valid request
                Session::forget('csrf_token');
            } else{
                die("This is not a valid request, CSRF token mismatch");
            }
        } else{
            die("Not a valid request CSRF token missing");
        }
    }
}