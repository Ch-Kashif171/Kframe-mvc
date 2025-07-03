<?php

namespace App\Controllers;

use App\Models\Users;

class HomeController extends Controller
{

    public function __construct(){}

    public function index()
    {
        $users = Users::gets();
        dd($users);
        return view('welcome');
    }
}
