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
Route::get('test','WastonController@test');

Route::group(['prefix' => 'waston'],function (){
    /**
     * 工作区部分
     */
    //获取工作区
    Route::get('get_ws','WastonController@getWorkspace');
    //添加工作区
    Route::post('add_ws','WastonController@addWs');
    /**
     * 意向部分
     */
    //获取意向
    Route::get('get_intents','WastonController@getIntent');
    //获取单个意向
    Route::get('get_one_intent','WastonController@getOneIntent');
    //创建意向
    Route::post('add_intents','WastonController@addIntent');
    //更新意向
    Route::post('update_intents','WastonController@updateIntent');
    //删除意向
    Route::get('del_intent','WastonController@delIntent');
    /**
     * 例子
     */
    //获取例子
    Route::get('get_examples','WastonController@getExample');
    //获取单个例子
    Route::get('get_one_examples','WastonController@getOneExample');
    //创建例子
    Route::post('add_examples','WastonController@addExample');
    //更新例子
    Route::post('update_examples','WastonController@updateExample');
    //删除例子
    Route::get('del_examples','WastonController@delExample');
    /**
     * 实体
     */
    //列出实体
    Route::get('get_entitys','WastonController@getEntity');
    //获取实体价值
    Route::get('get_one_entitys','WastonController@getOneEntity');
    //创建实体
    Route::post('add_entitys','WastonController@addEntity');
    //更新实体
    Route::post('update_entitys','WastonController@updateEntity');
    //删除实体
    Route::get('del_entitys','WastonController@delEntity');
    /**
     * dialog
     */
    //列出dialog
    Route::get('get_dialogs','WastonController@getDialog');
    //获取dialog
    Route::get('get_one_dialogs','WastonController@getOneDialog');
    //创建dialog
    Route::post('add_dialogs','WastonController@addDialog');
    //更新dialog
    Route::post('update_dialogs','WastonController@updateDialog');
    //删除dialog
    Route::get('del_dialogs','WastonController@delDialog');
    /**
     * 获取对用户输入的响应
     */
    Route::post('message','WastonController@message');
    /**
     * 获取工作区训练状态
     */
    Route::get('get_status','WastonController@getStatus');
});

Route::group(['prefix' => 'yj'],function() {
    /**
     * 工作区部分
     */
    //获取工作区
    Route::get('get_ws','YJController@getWs');
    //添加工作区
    Route::post('add_ws','WastonController@addWs');
});


