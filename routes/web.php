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
    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::post('/save-book', 'DashboardController@saveBook')->name('save-book');
    Route::post('/add-to-library', 'DashboardController@addToLibrary')->name('add-to-library');
    Route::post('/get-book', 'DashboardController@getBook')->name('get-book');
});
