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
    return dd(\App\Models\Schedule::getNext(3));
})->name('test');


Route::middleware(['auth'])->group(function() {
    Route::get('/home/{page?}', 'PageController@home')->name('home');
});

Route::view('/welcome', 'pages.welcome')->name('welcome');

Route::get('/', 'ChatController@index')->name('welcome');
Route::get('/chat', 'ChatController@index')->name('chat');

Auth::routes();
