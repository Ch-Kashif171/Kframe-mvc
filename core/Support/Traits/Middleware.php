<?php

namespace Core\Support\Traits;

use App\Kernel;
use Core\Exception\Handlers\MiddlewareException;

trait Middleware
{
    /**
     * @param $middlewares
     * @return bool
     * @throws MiddlewareException
     */
    public static function getMiddleware($middlewares) {

        $kernel = new Kernel();

        if (is_array($middlewares)) {

            foreach ($middlewares as $middleware) {

                if (isset($kernel->routeMiddleware[$middleware])) {
                    $middleware_class = new $kernel->routeMiddleware[$middleware]();
                    $result = $middleware_class->handle();
                    if ($result === false || $result === null) {
                        return false; // Stop execution if middleware returns false
                    }

                } else {
                    throw new MiddlewareException("Your given middleware did not match");
                }
            }

        } else {

            if (! is_null($middlewares)) {

                if (isset($kernel->routeMiddleware[$middlewares])) {

                    $middleware_class = new $kernel->routeMiddleware[$middlewares]();
                    $result = $middleware_class->handle();
                    if ($result === false || $result === null) {
                        return false; // Stop execution if middleware returns false
                    }

                } else {
                    throw new MiddlewareException("Your given middleware did not match");
                }

            }
        }
        
        return true; // All middleware passed
    }

    /**
     * @param $middlewares
     * @return void
     * @throws MiddlewareException
     */
    public function middleware($middlewares)
    {
        try {
            if (!static::getMiddleware($middlewares)) {
                exit;
            }
        } catch (MiddlewareException $e) {
            throw $e;
        }
    }

}