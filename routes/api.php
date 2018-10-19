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
Route::group(['prefix' => 'v1'],function(){
    Route::post('/deviceLog','DeviceController@create');
    Route::get('/devices','DeviceController@list');
    Route::post('/device','DeviceController@getDevice');
    Route::post('/localDevice','DeviceController@get_local_ip');
    Route::post('/globalDevice','DeviceController@get_global_ip');
    Route::post('/setPassword','DeviceController@set_passsword');
    Route::get('/ping','DeviceController@ping');
    });
