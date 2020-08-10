<?php

use CloudCreativity\LaravelJsonApi\Facades\JsonApi;
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

Route::prefix('api/v1/auth')->group(function () {
    Route::post('signup', 'AuthController@signup');
    Route::post('login', 'AuthController@login');

    Route::group(['middleware' => 'auth:api'], function () {
        Route::get('user', 'AuthController@user');
        Route::get('logout', 'AuthController@logout');
    });
});

JsonApi::register('default')->authorizer('defaultapi')->middleware('auth:api')->routes(function ($api)
{
    $api->resource('users')->only('index', 'read')->relationships(function ($relations) {
        $relations->hasMany('tasks', 'tags');
    });

    $api->resource('tasks')->relationships(function ($relations) {
        $relations->hasOne('users');
        $relations->hasMany('tags');
    });

    $api->resource('tags')->relationships(function ($relations) {
        $relations->hasMany('tasks');
    });
});
