<?php

namespace Core\Support\Traits\Csrf;
use Core\Exception\Handlers\CsrfException;
use Core\Support\Session;
use Core\Support\Traits\Csrf\VerifyCsrf;

trait csrfToken
{
    use VerifyCsrf;

    public static function check(){

        if (isset($_POST['csrf_token']) && Session::has('csrf_token')){
            if (static::verify(Session::get('csrf_token'))){ // this is valid request
                self::expireToken();
                // Rotate token after successful check
                Session::put('csrf_token', bin2hex(random_bytes(32)));
            } else{
                throw new CsrfException("This is not a valid request, CSRF token mismatch");
            }
        } else{
            throw new CsrfException("CSRF token missing or invalid token");
        }
    }

    private static function expireToken(){
        $expireAfter = 10;
        if(Session::has('last_action')){
            $secondsInactive = time() - Session::get('last_action');
            $expireAfterSeconds = $expireAfter * 60;
            if($secondsInactive >= $expireAfterSeconds){
                Session::forget('csrf_token');
            }
        }
        Session::put('last_action',time());
    }
}