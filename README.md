# Kfram

working on traits, using namespaces and make static functions for calling directoly from models like User::first(); etc, this will posibile to create Doctrine calss's functios statically.

#Builtin Facades:
There are some nice Facades like 

Captcha: There is available a Captcha Facade, so we can use this to render and verify captcha (helpers also available for this).

Toastr: There is a Facade for alert message in toastr.

Note:(first need to include a helper function called toastr() in html footer page) Then add Toastr Facade in any controller where you want to use it and then call its function like: 

Toastr::error('message') ,

Toastr::success('message') , 

Toastr::warning('message')

Toastr::info('message') 

e.t.c


#Helpers:
There are many default helper functions, like

captcha(): to render the captcha in html form directly.

verifyCaptcha(): to verify captcha.

#Direct access deny:

Include index,html in very directory to deny the directory listing.

Also add .htaccess in bootstrap directory to perventing direct access of autoload file

in autoload.php file define a constant called "root_path".

then add a line "define('root_path') OR exit('No direct script access allowed')" in each file at the top

Mail Facade:

e.g:

    Mail::send('mail', $data, function($mail) {
     
        $mail->to('test@gmail.com', 'test');
        $mail->subject('HTML Testing Mail');
        $mail->body('This is body');
        $mail->from('test@gmail.com','test');
        $mail->execute();
    });
