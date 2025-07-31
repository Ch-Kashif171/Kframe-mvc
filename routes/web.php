<?php

use App\Http\Controllers\HomeController;
use Core\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
