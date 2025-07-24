<?php


use Core\Support\Routing\Route;

try {
    Route::checkMethodNotAllowed();
} catch (Exception $e) {
} 