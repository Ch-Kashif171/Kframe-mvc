<?php

namespace App\Controllers;

use App\Controllers\Controller;
use App\Models\Users;
use Core\Support\DB;

class HomeController extends Controller
{

    public function __construct(){

    }

    public function index()
    {
        return view('welcome');
    }
}
