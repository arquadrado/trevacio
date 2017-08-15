<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function () {
	Route::get('/', function () {
		return redirect()->route('dashboard');
	});
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::post('/save-book', 'DashboardController@saveBook')->name('save-book');
    Route::post('/add-to-user-collection', 'DashboardController@addToUserCollection')->name('add-to-user-collection');
    Route::post('/get-book', 'DashboardController@getBook')->name('get-book');
    Route::get('/update-library', 'DashboardController@updateLibrary')->name('update-library');
    Route::post('/save-reading-session', 'DashboardController@saveReadingSession')->name('save-reading-session');
});
