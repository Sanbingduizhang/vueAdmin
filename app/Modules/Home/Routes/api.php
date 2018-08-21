<?php

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

//Route::get('/home', function (Request $request) {
//    // return $request->home();
//})->middleware('auth:api');
Route::group([
    'prefix' => 'home',
    'middleware' => ['cros','checktoken']],function(){
    //测试的几个接口
    Route::get('/test','HomeController@test')->name('home.test');
    Route::get('/weather','HomeController@weather')->name('home.weather');
    Route::get('/bd_go','HomeController@bd_go')->name('home.bd_go');
    /**
     * 主页显示接口
     */
    Route::get('/index_head','IndexController@index_head')->name('home.index_head');
});
Route::group([
    'prefix' => 'home',
    ],function(){
    /**
     * 主页显示接口
     */
    Route::get('/index_head','IndexController@index_head')->name('home.index_head');
    Route::get('/index_mid','IndexController@index_mid')->name('home.index_mid');
    Route::get('/index_right','IndexController@index_right')->name('home.index_right');
});

