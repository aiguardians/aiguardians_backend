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
    Route::get('/', 'PageController@index');
    Route::get('/chat', 'ChatController@index');
});

Route::get('/test', function() {
    $course = App\User::find(133);
    return dd($course->student->groups);
});

Auth::routes();
