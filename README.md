# Kframe

Kframe
PHP MVC framework. A basic mvc pattern framework, most functions name are same like Laravel (inspired by Laravel) I want to improve it with github community, please contribute and make it strong as much as possible. Any contribution will be appreciated.

# Getting Started
  Kframe uses Composer to Manage Dependencies and You need to have Composer installed on your machine to continue If you don't already have composer, Download it here: http://getcomposer.org/
  
  After Installing Composer, Run command
  
    composer update

# Database configuration
  1. Set database detail in .env file, then run migration command to create default users table.
  2. Define specific table for users authentication in .env "AUTH_TABLE" environment constant. like "AUTH_TABLE=users" 

# Environment

Set Environment variable "APP_ENV" in .env as development or production to show or hide errors, set by default is development.

We can set go home url for 404 error page link for production mode. 

# Routing:

We can define routes in route/route.php file as below.
   ```php
    Route::get('/','HomeController@index');
   ```
# Group Route

Group route to set prefix and namespace

   ```php
    Route::group(['prefix'=>'admin','namespace'=>'Admin'], function () {
        Route::get('dashboard','DashboardController@index');
    });
   ```
   
# Middleware
Create middleware in App\Middleware directory, define rules in middleware handler function then register it in App\Kernel.php.

Example:

   ```php
    public $routeMiddleware = [
        'auth' => Authenticate::class,
    ];
   ```
Then in route group function you can define middleware with array or with string.

Example:
 
   ```php
     Route::group(['middleware'=>'auth'], function () {
         Route::get('/','HomeController@index');
     });
   ```

Or

  ```php
     Route::group(['middleware'=>['auth','web']], function () {
         Route::get('/','HomeController@index');
     });
   ```
# Builtin Facades:
There are some nice Facades like 

Captcha: There is available a Captcha Facade, so we can use this to render and verify captcha (helpers also available for this).

Toastr: There is a Facade for alert message in toastr.

Note:(first need to include a helper function called toastr() in html footer page) Then add Toastr Facade in any controller where you want to use it and then call its function like: 
  ```php
    Toastr::error('message') ,

    Toastr::success('message') , 

    Toastr::warning('message')

    Toastr::info('message') 
  ```


Mail Facade:

e.g:
   ```php
    Mail::send('mail', [], function($mail) {
        $mail->to('example@gmail.com', 'Kframe');
        $mail->subject('HTML Testing Mail');
        $mail->from('example@gmail.com','Kframe');
        $mail->attachment('path','Kframe');
    });
   ```

# Builtin Pagination:
There are two type bootstrap base pagination provided by framework.
You can call on query builder as well as on model.

1. paginate()

2. simplePaginate();

Example:
   ```php  
    DB::table('users')->paginate(10);
   ```
    Or
   ```php
    $this->model('users')->paginate(10);
   ```
    
Then include below snippet to render the pagination on view page like:
   ```php
    <?php echo $render->links?>
   ```
    
# CSRF
There is a csrf token verification helper called csrf_toke() 
provided to add in each post form (it will include an input field with csrf token value)

example: 
   ```php
    <?php echo csrf_token(); ?>
   ```

    
# Helpers:
There are many default helper functions, like

captcha(): to render the captcha in html form directly.

verifyCaptcha(): to verify captcha.

# Direct access deny:

Include index.html in every directory to deny the directory listing.

Also add .htaccess in bootstrap directory to perverting direct access of autoload file

in autoload.php file define a constant called "root_path".

then add a line "define('root_path') OR exit('No direct script access allowed')" in each file at the top

# Commands:

Available commands:

For Auth Scaffolding:

    php kframe make:auth auth

Note: above command will create controllers in controllers/Auth, also will create authenticate route in route file 


For create a model:

    php kframe make:model model name
    
For create a Controller:

    php kframe make:controller controller name

Create migration for new table in "migrations/Migration.php" file
and then run following command:

    php kframe migration migrate
   
# Register Custom Helper:
For register custom helpers file in framework, make a directory under app, create a php file in this directory then for register this helper globally in framework include name in 'config/app.php' helpers like:
   ```php 
    'helpers' =>  array('helpers/my_helper'),
   ```

For more then one:
   ```php
    'helpers' =>  array('helpers/my_helper','helpers/my_other_helper'),
   ``` 
    
# Register Custom Libraries:
For register custom library file in framework, make a directory under app, create a php file in this directory then for register this library globally in framework include name in 'config/app.php' libraries like:
   ```php
    'libraries' =>  array('libraries/my_library'),
   ```
    
 For more then one:
   ```php
    'libraries' =>  array('libraries/my_library','libraries/other_library'),
   ```
