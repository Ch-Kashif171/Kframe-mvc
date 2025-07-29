<?php

namespace Core\Support\Traits\Csrf;

use Core\Exception\Handlers\CsrfException;
use Core\Support\Session;

trait CsrfToken
{
    public static function checkCsrf()
    {
        if (isset($_POST['csrf_token']) && Session::has('csrf_token')){
            if (!static::verify(Session::get('csrf_token'))){ // this is valid request
                throw new CsrfException("This is not a valid request, CSRF token mismatch");
            }
        } else {
            throw new CsrfException("CSRF token missing or invalid token");
        }
    }

    /**
     * @param $token
     * @return bool
     */
    public static function verify($token): bool
    {
        return $token === $_POST['csrf_token'];
    }

    public static function token(): mixed
    {
        if (Session::has('csrf_token')) {
            $token = Session::get('csrf_token');
        } else{
            $token = bin2hex(random_bytes(32));
            Session::put('csrf_token', $token);
        }
        return $token;
    }

    /**
     * @return void
     * @throws \Exception
     */
    private static function rotateToken()
    {
        $expireAfter = 10;
        if(Session::has('last_action')){
            $secondsInactive = time() - Session::get('last_action');
            $expireAfterSeconds = $expireAfter * 60;
            if($secondsInactive >= $expireAfterSeconds){
                Session::forget('csrf_token');
            }
        }
        Session::put('last_action',time());
        Session::put('csrf_token', bin2hex(random_bytes(32)));
    }

}
