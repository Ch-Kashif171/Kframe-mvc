<?php

namespace App\Middleware;

use Core\Support\Auth;

class Authenticate
{
    public function handle()
    {
        if (! Auth::check()) {
            return redirect('login');
        }
    }
}