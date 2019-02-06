<?php
defined('root_path') OR exit("Sorry! No direct script access allowed ");

use App\controllers\Controller;
use Core\Facades\DB;
use Core\Facades\Request;

class HomeController extends Controller
{

    public function __construct(){

    }

    public function index(){

        return view('welcome');
    }
}