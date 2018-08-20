<?php

use Illuminate\Http\Request;

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
    Route::get('/test','HomeController@test')->name('home.test');
});
Route::group(['middleware' => ['cros','checktoken']],function () {
    Route::get('/weather','HomeController@weather');
//    Route::get('/bd-go','HomeController@bd_go');
});
Route::get('/bd-go','HomeController@bd_go');

