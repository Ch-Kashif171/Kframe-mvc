<?php

namespace Core\Facades;

class Captcha
{

    public static function verify($captcha){
       return verifyCaptcha($captcha);
    }

    public static function get(){
        return captcha();
    }

}