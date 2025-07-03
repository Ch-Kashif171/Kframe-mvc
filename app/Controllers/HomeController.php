<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Users;

class HomeController extends Controller
{

    public function __construct(){

    }

    public function index()
    {
        $user = Users::select('name')->where('id', '=', '14')->get();
        dd($user);
        return view('welcome');
    }
}
