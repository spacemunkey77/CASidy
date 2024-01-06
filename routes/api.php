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

Route::middleware('auth:api')->post('doorbell','IftttWebhooksController@arlo');
Route::middleware('auth:api')->post('august','IftttWebhooksController@august');
Route::middleware('auth:api')->post('door','DoorController@august');
Route::middleware('auth:api')->post('gps','IftttWebhooksController@gpstrigger');
Route::middleware('auth:api')->post('button/press','IftttWebhooksController@button');
Route::middleware('auth:api')->put('sensors/device/{mac?}','KonnectedController@store');
Route::middleware('auth:api')->get('statuspage', 'StatusController@ajax');
