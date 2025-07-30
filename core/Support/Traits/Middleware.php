<?php

namespace Core\Support\Traits;

use App\Kernel;
use Core\Exception\Handlers\MiddlewareException;

trait Middleware
{

    public static function getMiddleware ($middlewares) {

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

    public function middleware($middleware) {
        try {
            $result = static::getMiddleware($middleware);
            if ($result === false) {
                exit; // Stop execution if middleware returns false
            }
        } catch (MiddlewareException $e) {
            throw new MiddlewareException($e->getMessage());
        }

    }

}