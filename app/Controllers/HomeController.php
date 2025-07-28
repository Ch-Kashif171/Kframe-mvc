<?php

namespace App\Controllers;

use App\Models\Users;

class HomeController extends Controller
{

    public function __construct()
    {
        //
    }

    public function index()
    {
        return view('welcome');
    }

}

