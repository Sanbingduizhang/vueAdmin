<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your module. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'test'],function () {
    /**
     * 工作区部分
     */
    //获取工作区
    Route::get('get_ws','WastonTestController@getWorkspace');
    //添加工作区
    Route::post('add_ws','WastonTestController@addWs');
});