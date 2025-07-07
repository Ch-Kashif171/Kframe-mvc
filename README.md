# Kframe

Kframe
PHP MVC framework. A basic mvc pattern framework, most functions name are same like Laravel (inspired by Laravel) I want to improve it with github community, please contribute and make it strong as much as possible. Any contribution will be appreciated.

# Getting Started
  Kframe uses Composer to Manage Dependencies and You need to have Composer installed on your machine to continue If you don't already have composer, Download it here: http://getcomposer.org/
  
  After Installing Composer, Run command
  
    composer install

# Database configuration
  1. Set database detail in .env file, then run migration command to create default users table.
  2. Define specific table for users authentication in .env "AUTH_TABLE" environment constant. like "AUTH_TABLE=users" 

# Environment

Set Environment variable "APP_ENV" in .env as development or production to show or hide errors, set by default is development.

We can set go back url for 404 error page for production mode in config\app.php.
Example:

   ```php
    $go_back = url();
   ```

# Routing:

We can define routes in route/route.php file as below.
   ```php
    Route::get('/', [App\Controllers\HomeController::class, 'index']);
   ```
# Group Route

Group route to set prefix and namespace

   ```php
    Route::group(['prefix'=>'admin','namespace'=>'Admin'], function () {
        Route::get('dashboard', [App\Controllers\HomeController::class, 'index']);
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
Then Any Controllers's constructor you can call middleware.

Example:
 
   ```php
      $this->middleware('auth');
   ```

For more than one middleware.

  ```php
      $this->middleware(['auth','web']);
   ```
# Template Structure

All code generation templates for controllers, models, routes, and views are now centralized under:

    core/Templates/

Organized as:
- core/Templates/Controllers/ (controller templates)
- core/Templates/Models/ (model templates)
- core/Templates/Routes/ (route templates)
- core/Templates/Views/auth/ (auth view templates)
- core/Templates/Views/partials/ (partials like header/footer)

Update your generator code and any custom scripts to reference these new locations for scaffolding.

# Builtin Support Classes:
There are some nice Support classes like:

Captcha: There is available a Captcha Support class, so we can use this to render and verify captcha (helpers also available for this).

Toastr: There is a Support class for alert message in toastr.

Note:(first need to include a helper function called toastr() in html footer page) Then add Toastr Support class in any Controllers where you want to use it and then call its function like: 
  ```php
    Toastr::error('message') ,
    Toastr::success('message') , 
    Toastr::warning('message')
    Toastr::info('message') 
  ```

Mail Support class:

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
    Users::paginate(10);
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

Note: above command will create controllers in core/Templates/Controllers/Auth, also will create authenticate route in core/Templates/Routes, and views in core/Templates/Views/auth.
Example:

   ```php
  Route::authenticate();
   ```
If you want to disable register route then add ```['register'=>false]```
Example:

  ```php
  Route::authenticate(['register' => false]);
```

For create a model:

    php kframe make:model model name
    
For create a Controller:

    php kframe make:controller ControllerName

## Creating and Running Migrations

To create a new migration file, use:

    php kframe make:migration create_table_name

Edit the generated file in the `migrations/` directory to define your schema in the `up()` and `down()` methods.

Then run all pending migrations with:

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

# Migrations

Kframe now supports a Laravel-like migration system for managing your database schema changes.

## Creating a Migration

To create a new migration file, use:

    php kframe make:migration create_users_table
    # or
    php kframe make:migration CreateUsersTable

- The generated file will be placed in the `migrations/` directory with a timestamped filename.
- The migration class name will be StudlyCase (e.g., `CreateUsersTable`).
- The table name will be automatically extracted as `users` (or `about_us` for `create_about_us_table`).

## Editing a Migration

Edit the generated migration file to define your schema in the `up()` and `down()` methods:

```php
public function up()
{
    Migrate::create('users', [
        $this->table->increments('id'),
        $this->table->string('name'),
        $this->table->timestamps(),
    ]);
}

public function down()
{
    Migrate::drop('users');
}
```

## Running Migrations

To run all pending migrations:

    php kframe migration migrate

- This will scan the `migrations/` directory and run the `up()` method for each migration that hasn't been run yet.
- Applied migrations are tracked in the `migrations` table in your database.
- You can safely add new migration files and rerun the command; only new migrations will be executed.

## Rolling Back Migrations

To roll back the most recent migration:

    php kframe migration rollback

- This will call the `down()` method of the latest applied migration and remove it from the `migrations` table.
- You can run this command multiple times to roll back multiple migrations, one at a time.

# Old Input Values (Form Repopulation)

Kframe automatically supports repopulating form fields with previous input values after validation errors or failed submissions.

## How it works
- On every POST request, all submitted input values are automatically flashed to the session.
- If validation or another error occurs, you can use the `old('field_name')` helper in your form fields to repopulate them with the previous input.
- After a successful submission (when you redirect back with a success message), the old input data is automatically cleared from the session.

## Example usage in forms
```php
<input type="text" name="name" value="<?php echo old('name'); ?>">
<input type="email" name="email" value="<?php echo old('email'); ?>">
```

## No manual setup needed
- You do not need to manually flash or clear old input data; it is handled by the framework for all POST requests and successful redirects.
