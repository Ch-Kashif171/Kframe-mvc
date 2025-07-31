<?php

namespace App\Middleware;

use Core\Support\Auth;

class Guest
{
    public function handle()
    {
        if (Auth::check()) {
            return redirect('/');
        }
        return true;
    }
}