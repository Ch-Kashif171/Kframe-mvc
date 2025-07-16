<?php

namespace Core\Controllers;

use Core\Support\Traits\Middleware;
use Core\Support\Traits\Modelable;

/**
 * Internal base controller used by the framework.
 * Includes core methods and traits like Middleware.
 * Do not extend this directly in your application.
 */
class BaseController
{
    use Middleware, Modelable;
}
