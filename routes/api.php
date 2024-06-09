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
Route::post('login', 'App\Http\Controllers\Api\AuthController@login');
Route::post('register', 'App\Http\Controllers\Api\AuthController@register');
Route::apiResource('blog-posts', 'App\Http\Controllers\Api\BlogController');
Route::apiResource('blog-categories', 'App\Http\Controllers\Api\CategoryController');
Route::apiResource('blog-tags', 'App\Http\Controllers\TagsApiController');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
