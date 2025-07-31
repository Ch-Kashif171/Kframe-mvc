<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class controllername extends Controller
{

    public function __construct()
    {
        //
    }

    public function index()
    {
        return view('welcome');
    }

    public function home()
    {
        return view('home');
    }

}

