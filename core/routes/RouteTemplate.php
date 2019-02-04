
/*auth routes*/
Route::get('login','Auth\LoginController@index');
Route::post('login','Auth\LoginController@login');
Route::get('logout','Auth\LoginController@logout');
Route::get('register','Auth\RegisterController@register');
Route::post('register','HomeController@save');
