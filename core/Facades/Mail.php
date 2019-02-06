<?php

namespace Core\Facades;
use Closure;
use Core\Facades\Mailing\SendMail;

class Mail
{
    public static function set($view,$data,Closure $closure){

         $closure (
            new SendMail (
                $view,
                $data
            )
         );

    }

}