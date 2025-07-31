<?php

namespace App\Http\Middleware;

use Core\Support\Auth;
use function redirect;

class Authenticate
{
    public function handle()
    {
        if (!Auth::check()) {
            return redirect('login');
        }
        return true;
    }
}