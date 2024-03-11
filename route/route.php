<?php

use Core\Route;

Route::get('/','HomeController@index');

/*auth routes*/
Route::authenticate();
