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

Route::get('/test', function (Request $request) {
    // return $request->test();
})->middleware('auth:api');
//Route::group(
//    ['prefix' => 'novel','middleware' => ['cros']],function() {
//        Route::get('/get','NovelController@getContent');
//}
//);
Route::post('/get','NovelController@getContent');