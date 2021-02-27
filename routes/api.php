<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', 'UserController@login');
Route::middleware('auth:api')->group(function () {
    Route::post('/sber_request', 'SberController@request');
    Route::post('/active_services', 'SberController@getActiveServices');

    Route::post('/notifyUser', 'NotifyUserController@push');
    Route::post('/subscribeDevice', 'SubscribeDeviceController@subscribe');
});

