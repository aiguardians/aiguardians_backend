<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/token', 'ChatController@token')->name('chat-speech-token');
Route::get('/query', 'ChatController@query')->name('chat-query');

Route::get('/group/{id}', 'GroupController@index');
Route::get('/group/{id}/schedule', 'ScheduleController@group');
