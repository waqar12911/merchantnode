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


Route::post("upload",[App\Http\Controllers\API\UserController::class,'upload']);

//merchant

Route::post('/merchants_login','App\Http\Controllers\API\UserController@merchants_login')->name('merchants_login');
Route::post('/merchants_Edit/{id}','App\Http\Controllers\API\UserController@merchants_Edit')->name('merchants_Edit');
Route::post('/merchant_maxboost','App\Http\Controllers\API\UserController@merchant_maxboost')->name('merchant_maxboost');

Route::post('/add-alpha-transction','App\Http\Controllers\API\AlphaController@addAlphaTransction')->name('addAlphaTransction');

// 2fa for merchant
Route::post('/check-merchant','App\Http\Controllers\API\UserController@checkMerchant')->name('checkMerchant');

Route::middleware('auth:api')->get('/user', function (Request $request) {return $request->user();});
