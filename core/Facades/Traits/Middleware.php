<?php

namespace Core\Facades\Traits;

use App\Kernel;
use Core\Exception\Handlers\MiddlewareNotFoundException;

trait Middleware
{

    public static function getMiddleware ($middlewares) {

        $kernel = new Kernel();

        if (is_array($middlewares)) {

            foreach ($middlewares as $middleware) {

                if (isset($kernel->routeMiddleware[$middleware])) {
                    $middleware_class = new $kernel->routeMiddleware[$middleware]();
                    $middleware_class->handle();

                } else {

                    throw new MiddlewareNotFoundException("Your given middleware did not match");
                }
            }

        } else {

            if (! is_null($middlewares)) {

                if (isset($kernel->routeMiddleware[$middlewares])) {

                    $middleware_class = new $kernel->routeMiddleware[$middlewares]();
                    $middleware_class->handle();

                } else {
                    throw new MiddlewareNotFoundException("Your given middleware did not match");
                }

            }
        }
    }

    public function middleware($middleware) {

        static::getMiddleware($middleware);
    }

}