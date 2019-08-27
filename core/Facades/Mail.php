<?php

namespace Core\Facades;
use Closure;
use Core\Facades\Mailing\SendMail;

class Mail
{
    public static function send($view,$data,Closure $closure){

        $mail = new SendMail (
            $view,
            $data
        );
         $closure (
             $mail
         );

        $mail->mailing();
    }

}