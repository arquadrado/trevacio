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
    Route::post('/remove-from-user-collection', 'DashboardController@removeFromUserCollection')->name('remove-from-user-collection');
    Route::post('/get-book', 'DashboardController@getBook')->name('get-book');
    Route::get('/update-library', 'DashboardController@updateLibrary')->name('update-library');
    Route::get('/update-user-info', 'DashboardController@updateUserInfo')->name('update-user-info');
    Route::post('/save-reading-session', 'DashboardController@saveReadingSession')->name('save-reading-session');
    Route::post('/delete-session', 'DashboardController@deleteReadingSession')->name('delete-session');
    Route::post('/delete-book', 'DashboardController@deleteBook')->name('delete-book');
    Route::post('/rate-book', 'DashboardController@rateBook')->name('rate-book');
    Route::post('/save-comment', 'DashboardController@saveComment')->name('save-comment');
    Route::post('/update-comment', 'DashboardController@updateComment')->name('update-comment');
});
