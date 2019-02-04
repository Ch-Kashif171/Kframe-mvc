<?php

use App\controllers\Controller;
use Core\Facades\DB;
use Core\Facades\Auth;
use Core\Facades\Mail;
use Core\Facades\Session;

class HomeController extends Controller
{

    public function __construct(){

    }

    public function home(){

        $data['user'] = $this->model('users')->paginate(5);

        Mail::send('mail', $data, function($mail) {

            $mail->subject('Kframe email');
            $mail->body('This is email body');
            $mail->to('kashif.sohail.el@gmail.com', 'Kashif');
            $mail->from('kashif.sohail.el@gmail.com','Kashif ele');
            $mail->execute();
        });


        return view('welcome',$data);
    }

    public function user(){

        $data['user'] = $this->model('users')->paginate(3);

        return view('welcome',$data);
    }
}