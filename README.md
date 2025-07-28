# Kframe

> **Lightweight. Laravel-Inspired. 100% Custom.**

Kframe is a **lightweight PHP MVC framework** inspired by Laravel, but built entirely from scratch. It's designed for developers who love Laravel's syntax and structure but want full control, performance, and simplicity.

Kframe is **not a Laravel clone**. It's a fresh micro-framework for small to medium web apps, dashboards, admin panels, and educational projects — without Composer bloat or hidden magic.

---

## 🚀 Why Kframe?

* ✅ Laravel-style routing, middleware, and validation
* ✅ Custom-built DI container and lifecycle
* ✅ CSRF protection and input sanitization
* ✅ Auth scaffolding, flash messages, old inputs
* ✅ CLI commands for models, controllers, and migrations
* ✅ Useful helpers: captcha, mail, toastr, pagination
* ✅ Simple, extendable, and easy to read/learn

---

## ✨ Features

* Auth Scaffolding (`Route::authenticate()`)
* Pagination: `paginate()` / `simplePaginate()`
* Flash messages (Toastr)
* Captcha: `captcha()` / `verifyCaptcha()`
* Old input repopulation: `old('field')`
* Eloquent-style Relationships: `hasOne()`, `hasMany()`, `belongsTo()` now supported in models

---

## 🛡️ Security

* ✅ **CSRF Protection**: `<?php csrf_field(); ?>` inside `<form>`
* ✅ **Output escaping**: `<?= e($value) ?>`
* ✅ **File upload validation**
* ✅ **Automatic input sanitization**

---

## 🧱 Installation

Make sure you have **PHP 8+** and **Composer** installed.

```bash
composer install
```

---

## 🔧 Environment Setup

Rename `.env.example` to `.env` and set the following:

```env
APP_ENV=development
DB_HOST=localhost
DB_DATABASE=kframe
DB_USERNAME=root
DB_PASSWORD=secret
AUTH_TABLE=users
```

Set `APP_ENV=production` to hide error output.

---

## 🌐 Routing

### Define routes in `routes/web.php`

```php
Route::get('/', [HomeController::class, 'index']);
```

### Route groups with prefix + middleware

```php
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('dashboard', [DashboardController::class, 'index']);
});
```

### Middleware per route

```php
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth');
```

---

## 🧰 Middleware System

Register middleware in `App\Kernel.php`:

```php
public $routeMiddleware = [
  'auth' => Authenticate::class,
  'web'  => WebMiddleware::class,
];
```

Use middleware in controllers:

```php
$this->middleware(['auth', 'web']);
```

---

## 📨 Mail Support

```php
Mail::send('mail', [], function($mail) {
    $mail->to('admin@example.com');
    $mail->subject('Welcome');
    $mail->from('noreply@example.com');
    $mail->attachment('path/to/file.pdf');
});
```

---

## 🧪 Validation

```php
$rules = [
  'email' => 'required|mail|unique:users,email',
  'password' => 'required|min:6|max:20'
];

$validation = Validator::validate($_POST, $rules);

if ($validation->fails()) {
  return redirect()->backwithErrors($validation->errors());
}
```

---

## 🧱 Migrations

### Create a new migration file

```bash
php kframe make:migration create_users_table
```

This will generate a file in the `migrations/` directory.

### Define the schema

Each migration file contains `up()` and `down()` methods. You can define your table structure using the `Blueprint` class inside the `up()` method:

```php
Migrate::create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name')->nullable();
    $table->string('email')->unique();
    $table->string('password');
    $table->timestamps();
});
```

### Rollback the table

In the `down()` method:

```php
Migrate::dropIfExists('users');
```

---

## 📦 CLI Commands

```bash
php kframe make:auth auth
php kframe make:model User
php kframe make:controller PostController
php kframe make:migration create_posts_table
php kframe migration:migrate
php kframe migration:rollback
```

---

## 🧮 Queries & ORM

Kframe offers a Laravel-inspired ORM for interacting with your database using expressive and chainable syntax.

### 🔍 Fetching Data

```php
// Get all users
$users = User::get();

// Find a specific user by ID
$user = User::find(1);

// Get users with conditions
$activeUsers = User::where('status', '=', 'active')->get();

// First matching result
$user = User::where('email', '=', 'john@example.com')->first();
```

### 🔒 Hidden Fields

To hide sensitive fields like passwords when converting models to arrays or JSON, use the `$hidden` property in your model:

```php
class User extends Model
{
    protected $hidden = ['password'];
}
```

This ensures that fields such as `password` or `other` are excluded when rendering user data in responses or views.

---

## 🔗 Defining Relationships

Define Laravel-style relationships directly in your models.

### One-to-One

```php
public function profile()
{
    return $this->hasOne(Profile::class, 'user_id');
}
```

### One-to-Many

```php
public function posts()
{
    return $this->hasMany(Post::class, 'user_id');
}
```

### Inverse (Belongs To)

```php
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
```

> 📝 **Note**: Eager loading is not yet supported but is planned for a future update.

---

## 🧩 Extending Routes

Register route files in `app/Providers/RouteServiceProvider.php`:

```php
public static function register(): array
{
    return [
        'routes/web.php',
        'routes/api.php',
        // Add more route files here...
    ];
}
```

Kframe will autoload them all.

---

## 🙌 Contribute

Want to improve this Laravel-style lightweight framework? Submit a PR or open an issue. All contributions are welcome!

---

## 📄 License

Kframe is open-source and licensed under the MIT license.

[![MIT License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)
[![Contributions welcome](https://img.shields.io/badge/contributions-welcome-brightgreen.svg)](CONTRIBUTING.md)