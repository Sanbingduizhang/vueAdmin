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

//Route::get('/admin', function (Request $request) {
//    // return $request->admin();
//})->middleware('auth:api');
Route::group([
    'prefix' => 'admin',
    'middleware' => ['cros','checktoken']],function(){
});
Route::group([
    'prefix' => 'cate',
    'middleware' => ['cros']],function () {
    //获取分类
    Route::get('index','CategoryController@index')->name('cate.index');
    //创建分类
    Route::post('create','CategoryController@create')->name('cate.create')->middleware('checktoken');
});
