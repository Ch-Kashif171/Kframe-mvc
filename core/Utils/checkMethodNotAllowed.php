<?php


use Core\Support\Facades\Route;

try {
    Route::checkMethodNotAllowed();
} catch (Exception $e) {
} 