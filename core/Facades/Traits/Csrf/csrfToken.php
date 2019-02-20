<?php

namespace Core\Facades\Traits\Csrf;
use Core\Exception\Handlers\CsrfException;
use Core\Facades\Session;
use Core\Facades\Traits\Csrf\VerifyCsrf;

trait csrfToken
{
    use VerifyCsrf;

    public static function check(){

        if (isset($_POST['csrf_token']) && Session::has('csrf_token')){
            if (static::verify(Session::get('csrf_token'))){ // this is valid request
                Session::forget('csrf_token');
            } else{
                throw new CsrfException("This is not a valid request, CSRF token mismatch");
            }
        } else{
            throw new CsrfException("CSRF token missing or invalid token");
        }
    }
}