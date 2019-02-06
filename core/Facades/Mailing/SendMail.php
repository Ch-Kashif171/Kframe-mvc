<?php

namespace Core\Facades\Mailing;
use Core\Mailer\PHPMailer;
use Core\Mailer\Exception;

class SendMail
{
    public $to;
    public $view;
    public $data;
    public $to_name;
    public $subject;
    public $body;
    public $from;
    public $from_name;
    public $attachment;
    public $attachment_name;

    public function to($to, $to_name){

        $this->to = $to;
        $this->to_name = $to_name;
        return $this;
    }

    public function subject($subject){

        $this->subject = $subject;
        return $this;
    }

    public function body($body){

        $this->body = $body;
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

    public function send(){
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
            //$mail->addReplyTo('kashif.sohail.el@gmail.com', 'Information');
            //->addCC('kashif.sohail.el@gmail.com');
            //$mail->addBCC('kashif.sohail.el@gmail.com');

            if (! is_null($this->attachment)){
                $mail->addAttachment($this->attachment,$this->attachment_name);
            }

            //Content
            $mail->isHTML(true);
            $mail->Subject = $this->subject;
            $mail->Body    = $this->body;

            $sent = $mail->send();

            return $sent ?? false;

        } catch (Exception $e) {
            echo 'Email could not be sent. Mailer Error: ', $mail->ErrorInfo;
            exit;
        }



    }

}