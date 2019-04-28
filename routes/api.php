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
    Route::resource('users', 'UserController');
    Route::patch('/users/password/{email}','UserController@resetPassword');

    //Route Category
    Route::resource('categories', 'CategoryController');

    //Route Unit
    Route::resource('units', 'UnitController');
});
