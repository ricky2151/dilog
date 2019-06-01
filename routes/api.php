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
    Route::get('/categories/{id}/goods','CategoryController@goodsCategory');

    //Route Unit
    Route::resource('units', 'UnitController');

    //Warehouse
    Route::resource('warehouses', 'WarehouseController');
    Route::get('/warehouses/{id}/racks','WarehouseController@racks');
    Route::get('/warehouses/{id}/goodsRacks','WarehouseController@goodsRacks');


    //Goods
    Route::resource('goods','GoodsController');
    Route::get('/goods/{id}/racks','GoodsController@racks');
    Route::get('/goods/{id}/sellingPrices','GoodsController@sellingPrices');
    Route::get('/goods/{id}/pricelists','GoodsController@pricelists');

    //Attributes
    Route::resource('attributes', 'AttributeController');

    //Types
    Route::resource('types', 'TypeController');

    //Cogs
    Route::resource('cogs', 'CogsController');

    //CogsComponent
    Route::resource('cogsComponent', 'CogsComponentController');
    
    //Source
    // Route::resource('sources', 'SourceController');

    //CategoryPriceSelling
    Route::resource('categoryPriceSellings', 'CategoryPriceSellingController');

    //Material
    Route::resource('materials', 'MaterialController');

    //Rack
    Route::resource('racks', 'RackController');

    //GoodsRack
    Route::resource('goodsRacks', 'GoodsRackController');

    //Supplier
    Route::resource('suppliers', 'SupplierController');

    //Division
    Route::resource('divisions', 'DivisionController');

    //Customer
    Route::resource('customers', 'CustomerController');
});
