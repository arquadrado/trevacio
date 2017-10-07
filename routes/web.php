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
})->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');

    Route::post('/save-book', 'BookController@saveBook')->name('save-book');
    Route::post('/delete-book', 'BookController@deleteBook')->name('delete-book');
    Route::post('/add-to-user-collection', 'BookController@addToUserCollection')->name('add-to-user-collection');
    Route::post('/rate-book', 'BookController@rateBook')->name('rate-book');
    Route::post('/save-reading-session', 'BookController@saveReadingSession')->name('save-reading-session');

    Route::post('/remove-from-user-collection', 'DashboardController@removeFromUserCollection')->name('remove-from-user-collection');
    Route::post('/get-book', 'DashboardController@getBook')->name('get-book');
    Route::get('/update-library', 'DashboardController@updateLibrary')->name('update-library');
    Route::get('/update-user-info', 'DashboardController@updateUserInfo')->name('update-user-info');
    Route::post('/delete-session', 'DashboardController@deleteReadingSession')->name('delete-session');
    Route::post('/save-comment', 'BookController@saveComment')->name('save-comment');
    Route::post('/update-comment', 'DashboardController@updateComment')->name('update-comment');
    
    Route::post('/save-color-scheme', 'DashboardController@saveColorScheme')->name('save-color-scheme');
    Route::post('/update-color-scheme', 'DashboardController@updateColorScheme')->name('update-color-scheme');
    Route::post('/delete-color-scheme', 'DashboardController@deleteColorScheme')->name('delete-color-scheme');
    Route::post('/set-color-scheme-default', 'DashboardController@setDefaultColorScheme')->name('set-color-scheme-default');
    
    Route::get('/get-activity', 'FeedController@getUserActivity')->name('get-activity');
});
