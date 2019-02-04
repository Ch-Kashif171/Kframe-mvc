<?php
namespace Core\Middleware;

use Core\Facades\Auth;

class Authenticated
{
    public function handle($request){
        if (!Auth::check()){
           return redirect('login');
        }else{
            return $request;
        }
    }

}