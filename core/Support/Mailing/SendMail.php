<?php

namespace Core\Support\Mailing;
use Core\Mailer\PHPMailer;
use Core\Mailer\Exception;

class SendMail
{
    public $to;
    public $to_name;
    public $subject;
    public $from;
    public $from_name;
    public $attachment;
    public $attachment_name;
    public $load_view;

    public function __construct($view,$data)
    {
        $this->load_view = view($view,$data,true);
    }

    public function to($to, $to_name){

        $this->to = $to;
        $this->to_name = $to_name;
        return $this;
    }

    public function subject($subject){

        $this->subject = $subject;
        return $this;
    }

    public function from($from, $from_name){

        $this->from = $from;
        $this->from_name = $from_name;
        return $this;
    }

    public function attachment($attachment, $attachment_name){

        $this->attachment = $attachment;
        $this->attachment_name = $attachment_name;
        return $this;
    }

    public function mailing(){
        $mail_config = require base_path().'/config/mail.php';

        $mail = new PHPMailer(true);

        try {

            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = $mail_config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mail_config['username'];
            $mail->Password = $mail_config['password'];
            $mail->SMTPSecure = $mail_config['encryption'];
            $mail->Port = $mail_config['port'];

            //Recipients
            $mail->setFrom($mail_config['from']['address'], $mail_config['from']['name']);
            $mail->addAddress($this->to, 'Kframe');

            if (! is_null($this->attachment)){
                $mail->addAttachment($this->attachment,$this->attachment_name);
            }

            //Content
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->load_view;

            $sent = $mail->send();

            return $sent ?? false;

        } catch (Exception $e) {
            throw $e;
        }



    }

}