<?php

use App\Controllers\HomeController;
use Core\Route;

Route::get('/', [HomeController::class, 'index']);

