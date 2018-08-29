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
    //获取相关工作区的状态
    Route::get('get_one_ws/{ws}','YJController@getOneWs');
    //添加工作区
    Route::post('add_ws','YJController@addWs');
    //更新工作区
    Route::post('up_ws/{ws}','YJController@upWs');
    //删除对应的工作区
    Route::get('del_ws/{ws}','YJController@delWs');
    /**
     * intents部分
     */
    //列出intents
    Route::get('get_intents','YJController@getIntents');
    //获取单个intents的内容
    Route::get('get_one_intents','YJController@getOneIntents');
    //添加intents
    Route::post('add_intents','YJController@addIntents');
    //更新intents
    Route::post('up_intents','YJController@upIntents');
    //删除intents
    Route::get('del_intents','YJController@delIntents');
    /**
     * examples部分
     */
    //列出examples
    Route::get('get_examples','YJController@getExamples');
    //列出examples单个信息
    Route::get('get_one_examples','YJController@getOneExamples');
    //创建examples
    Route::post('add_examples','YJController@addExamples');
    //更新examples
    Route::post('up_examples','YJController@upExamples');
    //删除examples
    Route::get('del_examples','YJController@delExamples');
    /**
     * counterexamples部分
     */
    //列出counterexamples
    Route::get('get_cexamples','YJController@getCexamples');
    //列出counterexamples单个信息
    Route::get('get_one_cexamples','YJController@getOneCexamples');
    //创建counterexamples
    Route::post('add_cexamples','YJController@addCexamples');
    //更新counterexamples
    Route::post('up_cexamples','YJController@upCexamples');
    //删除counterexamples
    Route::get('del_cexamples','YJController@delCexamples');
    /**
     * entities部分
     */
    //列出entities
    Route::get('get_entities','YJController@getEntity');
    //列出entities单个信息
    Route::get('get_one_entities','YJController@getOneEntity');
    //创建entities
    Route::post('add_entities','YJController@addEntity');
    //更新entities
    Route::post('up_entities','YJController@upEntity');
    //删除entities
    Route::get('del_entities','YJController@delEntity');

    /**
     * mentions部分
     */
    Route::get('mentions','YJController@mentions');

    /**
     * entity  values部分
     */
    //列出Value
    Route::get('get_values','YJController@getValue');
    //列出Value单个信息
    Route::get('get_one_values','YJController@getOneValue');
    //创建Value
    Route::post('add_values','YJController@addValue');
    //更新Value
    Route::post('up_values','YJController@upValue');
    //删除Value
    Route::get('del_values','YJController@delValue');
    /**
     * sysnonyms部分
     */


    /**
     * dialog部分
     */
    //列出dialog
    Route::get('get_dialog','YJController@getDialog');
    //列出dialog单个信息
    Route::get('get_one_dialog','YJController@getOneDialog');
    //创建dialog
    Route::post('add_dialog','YJController@addDialog');
    //更新dialog
    Route::post('up_dialog','YJController@upDialog');
    //删除dialog
    Route::get('del_dialog','YJController@delDialog');


    /**
     * 日志
     */
    Route::get('alllogs','YJController@getAllLogs');
    Route::get('onelogs','YJController@getOneLogs');




    //获取工作区的训练状态
    Route::get('get-status','YJController@getStatus');
    //根据用户的输入进行信息的输出
    Route::get('message','YJController@message');
});


