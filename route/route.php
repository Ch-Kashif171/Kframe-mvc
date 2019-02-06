<?php

Route::get('/','HomeController@index');

/*auth routes*/
Route::authenticate();

