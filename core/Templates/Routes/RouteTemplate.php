
Route::get('/home', [HomeController::class, 'home'])->middleware('auth');

/*auth routes*/
Route::authenticate();
