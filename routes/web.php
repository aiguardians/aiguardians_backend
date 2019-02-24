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


Route::middleware(['auth'])->group(function() {
});
Route::get('/', 'ChatController@index')->name('welcome');

Route::view('/welcome', 'pages.welcome')->name('welcome');
Route::view('/home', 'pages.home')->name('home');
Route::get('/chat', 'ChatController@index')->name('chat');

Route::get('/test', function() {
    return dd(\App\Models\Schedule::getNext(1));
})->name('test');



Auth::routes();
