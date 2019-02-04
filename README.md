# Kframe
PHP MVC framework.
I have developed a very very basic mvc pattteren framework, most functions name are same like Laravel (inspired by Laravel)
I want to improve it with github community,
please countribute and make it strong as much as possible.
This is my first try for mvc. Any contribution will be appreciated. 

#Builtin Facades:
There are some nice Facades like 

Captcha: There is available a Captcha Facade, so we can use this to render and verify captcha (helpers also available for this).

Toastr: There is a Facade for alert message in toastr.

Note:(first need to include a helper function called toastr() in html footer page) Then add Toastr Facade in any controller where you want to use it and then call its function like: 

Toastr::error('message'),

Toastr::success('message'), 

Toastr::warning('message')

Toastr::info('message') 


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
