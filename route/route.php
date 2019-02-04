<?php
/*auth routes*/

Route::get('/','HomeController@home');

Route::get('login','Auth\LoginController@index');
Route::post('login','Auth\LoginController@login');
Route::get('logout','Auth\LoginController@logout');
Route::get('register','Auth\RegisterController@register');
Route::post('register','Auth\RegisterController@save');
Route::get('user','HomeController@user');

Route::get('ajax','AjaxController@index');
