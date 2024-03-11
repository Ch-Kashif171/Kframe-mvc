<?php
defined('root_path') OR exit("Sorry! No direct script access allowed ");

use App\Controllers\Controller;

class HomeController extends Controller
{

    public function __construct(){

    }

    public function index(){
        return view('welcome');
    }
}
