<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Kframe – Lightweight PHP MVC Framework</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 960px;
            margin: auto;
            padding: 2rem;
        }
        h1 {
            font-size: 2.5rem;
            color: #1f2937;
        }
        h2 {
            font-size: 1.8rem;
            color: #111827;
            margin-top: 2.5rem;
        }
        h3 {
            margin-top: 1.5rem;
            font-size: 1.3rem;
        }
        p {
            margin-bottom: 1rem;
        }
        ul {
            padding-left: 1.5rem;
            margin-bottom: 1rem;
        }
        li {
            margin-bottom: 0.5rem;
        }
        code {
            background-color: #f1f5f9;
            padding: 0.2em 0.4em;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.95rem;
        }
        pre {
            background-color: #ebebeb;
            padding: 1rem;
            border-left: 4px solid #2d496e;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.95rem;
            overflow-x: auto;
            color: #111;
        }
        hr {
            border: none;
            border-top: 1px solid #ddd;
            margin: 2rem 0;
        }
        .note {
            color: #4b5563;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Kframe</h1>
    <p class="note">Lightweight. Laravel-Inspired. 100% Custom.</p>
    <p>
        Kframe is a <strong>lightweight PHP MVC framework</strong> inspired by Laravel, but built entirely from scratch.
        It's designed for developers who love Laravel's syntax and structure but want full control, performance, and simplicity.
    </p>
    <p>
        Kframe is <strong>not a Laravel clone</strong>. It's a fresh micro-framework for small to medium web apps, dashboards,
        admin panels, and educational projects — without Composer bloat or hidden magic.
    </p>

    <hr>

    <h2>🚀 Why Kframe?</h2>
    <ul>
        <li>✅ Laravel-style routing, middleware, and validation</li>
        <li>✅ Custom-built DI container and lifecycle</li>
        <li>✅ CSRF protection and input sanitization</li>
        <li>✅ Auth scaffolding, flash messages, old inputs</li>
        <li>✅ CLI commands for models, controllers, and migrations</li>
        <li>✅ Useful helpers: captcha, mail, toastr, pagination</li>
        <li>✅ Simple, extendable, and easy to read/learn</li>
    </ul>

    <hr>

    <h2>🛡️ Security</h2>
    <ul>
        <li>✅ CSRF Protection: <code>&lt;?php csrf_field(); ?&gt;</code> inside <code>&lt;form&gt;</code></li>
        <li>✅ Output escaping: <code>&lt;?= e($value) ?&gt;</code></li>
        <li>✅ File upload validation</li>
        <li>✅ Automatic input sanitization</li>
    </ul>

    <hr>

    <h2>✨ Features</h2>
    <ul>
        <li>Auth Scaffolding (<code>Route::authenticate()</code>)</li>
        <li>Pagination: <code>paginate()</code> / <code>simplePaginate()</code></li>
        <li>Flash messages (Toastr)</li>
        <li>Captcha: <code>captcha()</code> / <code>verifyCaptcha()</code></li>
        <li>Old input repopulation: <code>old('field')</code></li>
    </ul>

    <hr>

    <h2>🧱 Installation</h2>
    <p>Make sure you have <strong>PHP 8+</strong> and <strong>Composer</strong> installed.</p>
    <pre>composer install</pre>

    <hr>

    <h2>🔧 Environment Setup</h2>
    <p>Rename <code>.env.example</code> to <code>.env</code> and set the following:</p>
    <pre>APP_ENV=development
DB_HOST=localhost
DB_DATABASE=kframe
DB_USERNAME=root
DB_PASSWORD=secret
AUTH_TABLE=users</pre>
    <p>Set <code>APP_ENV=production</code> to hide error output.</p>

    <hr>

    <h2>🌐 Routing</h2>
    <h3>Define routes in <code>routes/web.php</code></h3>
    <pre>Route::get('/', [HomeController::class, 'index']);</pre>

    <h3>Route groups with prefix + middleware</h3>
    <pre>Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  Route::get('dashboard', [DashboardController::class, 'index']);
});</pre>

    <h3>Middleware per route</h3>
    <pre>Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth');</pre>

    <hr>

    <h2>🧰 Middleware System</h2>
    <p>Register middleware in <code>App\Kernel.php</code>:</p>
    <pre>public $routeMiddleware = [
  'auth' => Authenticate::class,
  'web'  => WebMiddleware::class,
];</pre>
    <p>Use middleware in controllers:</p>
    <pre>$this->middleware(['auth', 'web']);</pre>

    <hr>

    <h2>📨 Mail Support</h2>
    <pre>Mail::send('mail', [], function($mail) {
  $mail->to('admin@example.com');
  $mail->subject('Welcome');
  $mail->from('noreply@example.com');
  $mail->attachment('path/to/file.pdf');
});</pre>

    <hr>

    <h2>🧪 Validation</h2>
    <pre>$rules = [
  'email' => 'required|mail|unique:users,email',
  'password' => 'required|min:6|max:20'
];

$validation = Validator::validate($_POST, $rules);

if ($validation->fails()) {
  return redirect()->backwithErrors($validation->errors());
}</pre>

    <hr>

    <h2>🧱 Migrations</h2>
    <h3>Create a new migration file</h3>
    <pre>php kframe make:migration create_users_table</pre>

    <h3>Define the schema</h3>
    <pre>Migrate::create('users', function (Blueprint $table) {
  $table->increments('id');
  $table->string('name')->nullable();
  $table->string('email')->unique();
  $table->string('password');
  $table->timestamps();
});</pre>

    <h3>Rollback the table</h3>
    <pre>Migrate::dropIfExists('users');</pre>

    <hr>

    <h2>📦 CLI Commands</h2>
    <pre>php kframe make:auth auth
php kframe make:model User
php kframe make:controller PostController
php kframe make:migration create_posts_table
php kframe migration:migrate
php kframe migration:rollback</pre>

    <hr>

    <h2>🔗 Defining Relationships</h2>
    <h3>Define Laravel-style relationships directly in your models.</h3>
    <h3>One-to-One</h3>
    <pre>
public function profile()
{
    return $this->hasOne(Profile::class, 'user_id');
}
</pre>

    <h3>One-to-Many</h3>
    <pre>
public function posts()
{
    return $this->hasMany(Post::class, 'user_id');
}
</pre>

    <h3>Inverse (Belongs To)</h3>
<pre>
public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
</pre>

<blockquote>
📝 Note: Eager loading is not yet supported but is planned in a future update.
</blockquote>


<h2>🧩 Extending Routes</h2>
<p>Register route files in <code>app/Providers/RouteServiceProvider.php</code>:</p>
<pre>
public static function register(): array
{
    return [
        'routes/web.php',
        'routes/api.php',
        // Add more route files here...
    ];
}
</pre>
    <p>Kframe will autoload them all.</p>

    <hr>

    <h2>🙌 Contribute</h2>
    <p>Want to improve this Laravel-style lightweight framework? Submit a PR or open an issue. All contributions are welcome!</p>

    <hr>

    <h2>📄 License</h2>
    <p>Kframe is open-source and licensed under the MIT license.</p>
</div>
</body>
</html>
