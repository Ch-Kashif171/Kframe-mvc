<?php

namespace Core\Support;
use Closure;
use Core\Support\Mailing\SendMail;

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