<?php

namespace App\Middleware;

use Core\Facades\Auth;

class Authenticate
{
    public function handle()
    {
        if (! Auth::check()) {
            return redirect('login');
        }
    }
}