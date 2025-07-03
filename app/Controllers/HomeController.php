<?php

namespace App\Controllers;

class HomeController extends Controller
{

    public function __construct(){}

    public function index()
    {
        return view('welcome');
    }
}
