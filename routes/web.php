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
Route::get('/test', function() {
    return "asd";
})->name('test');




Route::middleware(['auth'])->group(function() {
    Route::view('/test/video', 'pages.video')->name('test');
    Route::get('/home/{page?}', 'PageController@home')->name('home');
    Route::get('/chat', 'ChatController@index')->name('chat');
});

Route::view('/welcome', 'pages.welcome')->name('welcome');
Route::view('/', 'pages.welcome')->name('welcome');


Auth::routes();
