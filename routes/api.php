<?php

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
    Route::post('payload', 'AuthController@payload');
});

Route::group([
    'namespace' => 'api',
    'middleware'=> 'jwt.auth'
], function () {
    //Route User
    Route::get('/users','UserController@index');
    Route::post('/users','UserController@store');
    Route::get('/users/{id}','UserController@show');
    Route::patch('/users/{id}','UserController@update');
    Route::patch('/users/password/{email}','UserController@resetPassword');
    Route::delete('/users/{id}','UserController@destroy');

    //Route Category
    Route::get('/categories','CategoryController@index');
    Route::post('/categories','CategoryController@store');
    Route::get('/categories/{id}','CategoryController@show');
    Route::patch('/categories/{id}','CategoryController@update');
    Route::delete('/categories/{id}','CategoryController@destroy');
});

