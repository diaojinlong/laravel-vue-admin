<?php

use Illuminate\Support\Facades\Route;

//不需要登录操作的方法
Route::group([
    'prefix' => config('laravel-vue-admin.route.prefix'),
    'middleware' => config('laravel-vue-admin.route.middleware'),
    'namespace' => '\DiaoJinLong\LaravelVueAdmin\Controllers'
], function () {
    Route::post('auth/login', 'AuthController@login');
});

//需要登录鉴权的方法
Route::group([
    'prefix' => config('laravel-vue-admin.route.prefix'),
    'middleware' => config('laravel-vue-admin.route.middleware_auth'),
    'namespace' => '\DiaoJinLong\LaravelVueAdmin\Controllers'
], function () {

    Route::get('index', 'IndexController@index');
    Route::post('auth/logout', 'AuthController@logout');
    Route::get('auth/info', 'AuthController@info');

});
