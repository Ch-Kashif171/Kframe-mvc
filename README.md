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

We can set back url for 404 error page for production mode in Core\Providers\RouteServiceProvider.php.
Example:

   ```php
    public const BACK_URL = '/';

    public const HOME = '/home';
   ```

# Routing:

We can define routes in routes/route.php file as below.
   ```php
    Route::get('/', [App\Controllers\HomeController::class, 'index']);
   ```
# Group Route

Group route to set prefix and namespace

   ```php
    Route::group(['prefix'=>'admin'], function () {
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

## Route Middleware

You can also apply middleware directly to routes, similar to Laravel:

### Individual Route Middleware

```php
// Single middleware
Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth');

// Multiple middleware
Route::get('/admin', [AdminController::class, 'index'])->middleware(['auth', 'admin']);
```

### Group Middleware

```php
// Apply middleware to all routes in a group
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
    Route::get('users', [UserController::class, 'index']);
});

// Multiple middleware in group
Route::group(['prefix' => 'api', 'middleware' => ['auth', 'api']], function () {
    Route::get('data', [ApiController::class, 'getData']);
});
```

### Controller Middleware

You can still use middleware in controller constructors:

```php
public function __construct()
{
    $this->middleware('auth');
}
```

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
    
# Security

Kframe now includes several important security features by default:

- **Input Sanitization:** All input from ` _POST` and ` _GET` is automatically sanitized in the `Request` class.
- **CSRF Protection:** CSRF tokens are automatically enforced for all POST requests at the routing layer. You must include the CSRF token in every POST form using `<?php csrf_token(); ?>`.
- **File Upload Validation:** Use the `validateFile()` method in the `Request` class to validate file uploads (type, size, errors) before processing or moving files.
- **Output Escaping:** Use the global `e()` helper function to escape user-supplied data in your views and prevent XSS:
  ```php
  <?= e($user['name']) ?>
  ```

# CSRF
There is a csrf token verification helper called csrf_token() provided to add in each post form (it will include an input field with csrf token value).

**CSRF protection is now enforced automatically for all POST requests.**

Example:
   ```php
    <?php echo csrf_token(); ?>
   ```

    
# Helpers:
There are many default helper functions, like

captcha(): to render the captcha in html form directly.

verifyCaptcha(): to verify captcha.

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

### Migration Syntax

You can now define your migrations using a callback and the `Blueprint` class:

```php
Migrate::create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name')->nullable();
    $table->timestamps();
});
```
- The migration system will automatically detect the migration file name for tracking, so you do not need to pass a third argument.
- Chained methods like `nullable()` and `unique()` work as expected.

To drop a table in your `down()` method:

```php
Migrate::dropIfExists('users');
```

Then run all pending migrations with:

    php kframe migration:migrate

Run below command to rollback the migrations:

    php kframe migration:rollback

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

# Validation Rules

Kframe provides a simple validation system for validating form input. You can use the `Validator::validate()` method to check your data against a set of rules.

## Usage Example
```php
$fields = [
    'email' => 'user@example.com',
    'password' => 'secret',
];

$rules = [
    'email' => 'required|mail',
    'password' => 'required|min:6|max:20',
];

$validator = Validator::validate($fields, $rules);

if($validation->fails()){
    return redirect()->backwithErrors($validation->errors());
}
```

## Available Validation Rules
- `required` &mdash; The field must not be empty.
- `mail` &mdash; The field must be a valid email address.
- `unique:table,column[,exceptId[,idColumn]]` &mdash; The field value must be unique in the specified database table and column. Optionally, you can exclude a specific ID (useful for updates) and specify the ID column name.
- `date` &mdash; The field must be a valid date in `d-m-Y` format.
- `min:N` &mdash; The field must be at least N characters long.
- `max:N` &mdash; The field must be no more than N characters long.
- `numeric` &mdash; The field must be numeric.
- `regex:/pattern/` &mdash; The field must match the given regular expression pattern.

## Example with Unique Rule
```php
$rules = [
    'email' => 'required|mail|unique:users,email',
];
```

## Getting Validation Errors
- `$validator->fails()` &mdash; Returns `true` if there are validation errors.
- `$validator->messages()` &mdash; Returns all error messages as a string (with `<br>` line breaks).
- `$validator->errors()` &mdash; Returns all error messages as an array.
- `$validator->error('field')` &mdash; Returns the error message for a specific field.
- `$validator->first()` &mdash; Returns the first error message.

# Kframe Route Registration

## Registering Route Files

To register your application's route files, follow these steps:

1. **Edit `app/Providers/RegisterRoutes.php`**

   This file contains a static `register()` method that returns an array of all your route files:

   ```php
   <?php
   
   namespace App\Providers;
   
   class RegisterRoutes
   {
       public static function register(): array
       {
           return [
               'routes/web.php',
               // Add more route files here...
           ];
       }
   }
   ```

- Add any new route files to this array.

- To add a new route file, just add it to the array in `app/Providers/RegisterRoutes.php`.
- These will load all your route files automatically


For more advanced usage, you can organize your routes into multiple files and simply add them to the array. No need to touch the core or autoload logic beyond the initial setup.

---

## No manual setup needed
- You do not need to manually flash or clear old input data; it is handled by the framework for all POST requests and successful redirects.
