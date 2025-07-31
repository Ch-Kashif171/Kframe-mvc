<?php

namespace Core\Foundation;

use App\Exceptions\Handler;
use Core\Exception\Handlers\MiddlewareException;
use Core\Exception\Handlers\NotFoundException;
use Core\Exception\Handlers\RouteNotFoundException;
use Core\Exception\Log;
use Core\Exception\Whoops;
use Core\Support\AssetsNotFound;
use Core\Support\Facades\Route;
use Core\Support\LoadEnv;
use Core\Support\Routing\RegisterAllRoutes;
use Core\Support\Routing\Router;

class Application
{
    const VERSION = '4.x';

    const FRAMEWORK = 'kframe';

    protected array $bindings = [];

    /**
     * @var array|string[]
     */
    protected array $includes = [
        '/core/Utils/helpers.php',
        '/config/app.php',
        // Add other files to include before singletons here
    ];

    /**
     * @var array|string[]
     */
    protected array $postIncludes = [
        '/config/mail.php',
        // Add other files to include after singletons here
    ];

    /**
     * @var array|string[]
     */
    protected array $notFound = [
        'routeExist' => '/core/Utils/routeExist.php',

    ];

    /**
     * @return string
     */
    public static function version(): string
    {
        return static::VERSION;
    }

    /**
     * @return string
     */
    public static function framework(): string
    {
        return static::FRAMEWORK;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        return $this->bindings[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $concrete
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public function bind(string $key, mixed $concrete, array $args = []): mixed
    {
        if (!isset($this->bindings[$key])) {
            if (is_callable($concrete)) {
                $this->bindings[$key] = $concrete();
            } elseif (is_string($concrete) && class_exists($concrete)) {
                $reflection = new \ReflectionClass($concrete);
                $this->bindings[$key] = $reflection->newInstanceArgs($args);
            } elseif (is_string($concrete) && file_exists($concrete)) {
                $this->bindings[$key] = require_once $concrete;
            } else {
                $this->bindings[$key] = $concrete;
            }
        }
        return $this->bindings[$key];
    }

    /**
     * @param string $key
     * @param mixed $concrete
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public function singleton(string $key, mixed $concrete, array $args = []): mixed
    {
        return $this->bind($key, $concrete, $args);
    }

    /**
     * Boot the application: load files and register services in order.
     * @throws \ReflectionException
     */
    public function boot(): void
    {

        foreach ($this->includes as $file) {
            $this->includeFile($file);
        }


        $this->registerSingletons();

        if (config('app.app_env') !== 'production') {
            $this->registerExceptionHandler();
        }

        foreach ($this->postIncludes as $file) {
            $this->includeFile($file);
        }
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    protected function registerSingletons(): void
    {
        $this->singleton('dotenv', LoadEnv::class, [root_path]);
        $this->singleton('whoops', [Whoops::class, 'handler']);
        $this->singleton('assetsNotFound', [AssetsNotFound::class, 'run']);
        // Add more singletons here as needed
    }

    /**
     * Helper to include a file from root_path.
     */
    protected function includeFile(string $path): void
    {
        require_once root_path . $path;
    }

    /**
     * @return bool
     */
    public function init(): bool
    {
        $this->includeFiles();

        // Bind route facade
        app('router', new Router());

        // Initialize all routes
        RegisterAllRoutes::loadAll();

        // Try to execute the matched route
        $routeMatched = false;
        try {
            $routeMatched = Route::executeRoutes();
        } catch (MiddlewareException | RouteNotFoundException $e) {
            Log::error($e, "Not Found Exception");
            throw new MiddlewareException($e->getMessage());
        }

        // If no route matched, handle 404 or method not allowed
        if (!$routeMatched) {
            require_once root_path . $this->notFound['routeExist'];
        }

        return true;
    }

    /**
     * @return void
     */
    protected function includeFiles()
    {
        foreach ($this->includes as $file) {
            require_once root_path . $file;
        }
    }

    protected function registerExceptionHandler(): void
    {
        $handler = new Handler(!(config('app.app_env') === 'production'));

        set_exception_handler([$handler, 'handle']);
    }

}