<?php

namespace App\Middleware;

use Core\Facades\Auth;
use Closure;

class Authenticate
{
    public static function handle()
    {
        if (! Auth::check()) {
            return redirect('login');
        }
    }
}