<?php

use App\Controllers\HomeController;
use Core\Support\Routing\Route;

Route::get('/', [HomeController::class, 'index']);

