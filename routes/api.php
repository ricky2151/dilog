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
    Route::get('/users/email/available/{email}','UserController@isEmailAvailable');

    //Route Category
    Route::resource('categories', 'CategoryController');

    //Route Unit
    Route::resource('units', 'UnitController');

    //Warehouse
    Route::resource('warehouses', 'WarehouseController');

    //Goods
    Route::get('goods','GoodsController@index');

    //Attributes
    Route::resource('attributes', 'AttributeController');

    //Types
    Route::resource('types', 'TypeController');

    //Cogs
    Route::resource('cogs', 'CogsController');

    //CogsComponent
    Route::resource('cogsComponent', 'CogsComponentController');
    
    //Source
    Route::resource('sources', 'SourceController');

    //CategoryPriceSelling
    Route::resource('categoryPriceSellings', 'CategoryPriceSellingController');
});
