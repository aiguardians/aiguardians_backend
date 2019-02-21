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
    Route::get('/', 'ChatController@index')->name('home');
});
Route::get('/chat', 'ChatController@index')->name('chat');

Route::get('/test', function() {
    // $r = App\Models\Schedule::first();
    // return dd(\Carbon\Carbon::now(), new \Carbon\Carbon($r->start_time));
    return dd(App\Models\Schedule::getNext(2));
})->name('test');



Auth::routes();
