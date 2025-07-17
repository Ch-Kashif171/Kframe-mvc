<?php

use App\Controllers\HomeController;
use Core\Support\Route;

Route::get('/', [HomeController::class, 'index']);

