<?php

namespace Core\Facades\Traits\Csrf;

trait VerifyCsrf
{
    public static function verify($token)
    {
        if ($token === $_POST['csrf_token']){
            return true;
        } else {
            return false;
        }
    }

}