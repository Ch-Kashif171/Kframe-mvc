<?php

namespace Core\Foundation;

use Core\Exception\Handlers\MiddlewareNotFoundException;
use Core\Exception\Handlers\RouteNotFoundException;
use Core\Exception\Log;
use Core\Support\Route;

class Application
{
    const VERSION = '1.0.2';

    const FRAMEWORK = 'kframe';

    protected array $bindings = [];

    /**
     * @var array|string[]
     */
    protected array $includes = [
        '/core/Utils/helpers.php',
        '/config/app.php',
        '/core/Exception/whoopsExceptionRegister.php',
        '/core/Utils/assetsNotFount.php',
        '/config/mail.php',
    ];

    /**
     * @var array|string[]
     */
    protected array $notFound = [
        'notAllowed' => '/core/Utils/checkMethodNotAllowed.php',
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
     * @param $concrete
     * @return mixed
     */
    public function bind($key, $concrete): mixed
    {
        if (!isset($this->bindings[$key])) {
            if (is_object($concrete) || is_callable($concrete)) {
                // Store object instance or closure directly
                $this->bindings[$key] = $concrete;
            } elseif (is_string($concrete) && class_exists($concrete)) {
                // Instantiate class if class name is given
                $this->bindings[$key] = new $concrete();
            } elseif (is_string($concrete) && file_exists($concrete)) {
                // Fallback: require file for legacy support
                $this->bindings[$key] = require_once $concrete;
            } else {
                // Store as is (could be array, scalar, etc.)
                $this->bindings[$key] = $concrete;
            }
        }
        return $this->bindings[$key];
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
     * @return bool
     */
    public function init(): bool
    {
        $this->includeFiles();

        // Initialize all routes
        Route::init();

        // Try to execute the matched route
        $routeMatched = false;
        try {
            $routeMatched = Route::executeRoutes();
        } catch (MiddlewareNotFoundException | RouteNotFoundException $e) {
            Log::error($e, "Not Found Exception");
            return true;
        }

        // If no route matched, handle 404 or method not allowed
        if (!$routeMatched) {
            require_once root_path . $this->notFound['notAllowed'];
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

}