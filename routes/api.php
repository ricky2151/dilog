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
    Route::get('/warehouses/{id}/stockOpnames','WarehouseController@showStockOpnames');
    Route::post('/warehouses/{id}/stockOpnames','WarehouseController@storeStockOpnames');
    Route::delete('/warehouses/stockOpnames/{stockOpnamesId}','WarehouseController@destroyStockOpnamesDetails');
    Route::post('/warehouses/stockOpnames/{stockOpnamesId}/stockOpnamesDetails','WarehouseController@storeStockOpnamesDetails');
    Route::patch('/warehouses/stockOpnames/{stockOpnamesId}/stockOpnamesDetails','WarehouseController@updateStockOpnamesDetails');
    Route::get('/warehouses/stockOpnames/{stockOpnamesId}/stockOpnamesDetails/edit','WarehouseController@editStockOpnamesDetails');
    Route::get('/warehouses/{warehouseId}/stockOpnames/stockOpnamesDetails/create','WarehouseController@createStockOpnamesDetails');
    Route::post('/warehouses/stockOpnames/{stockOpnamesId}/setWaitings','WarehouseController@setWaitings');


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
    Route::get('/racks/{id}/goodsRacks','RackController@goodsRacks');

    //GoodsRack
    Route::resource('goodsRacks', 'GoodsRackController');

    //Supplier
    Route::resource('suppliers', 'SupplierController');

    //Division
    Route::resource('divisions', 'DivisionController');

    //Periode
    Route::resource('periodes', 'PeriodeController');

    //MaterialRequest
    Route::resource('materialRequests', 'MaterialRequestController');

    //Customer
    Route::resource('customers', 'CustomerController');

    //PurchaseRequest
    Route::resource('purchaseRequests', 'PurchaseRequestController');

    //PurchaseOrder
    Route::resource('purchaseOrders', 'PurchaseOrderController');
    Route::patch('/purchaseOrders/{id}/approve','PurchaseOrderController@approve');
    Route::patch('/purchaseOrders/{id}/submit','PurchaseOrderController@submit');
    Route::get('/purchaseOrders/{id}/payments','PurchaseOrderController@getPayments');
    Route::get('/purchaseOrders/{id}/purchaseOrderDetails','PurchaseOrderController@purchaseOrderDetail');

    //PurchaseOrderDetail
    // Route::get('/purchaseOrders/{purchaseOrderId}/purchaseOrderDetails/create','PurchaseOrderDetailController@create');

    //PurchaseOrderDetail
    Route::resource('purchaseOrderDetails', 'PurchaseOrderDetailController');

    //Spbm
    Route::resource('spbms', 'SpbmController');

    //Payment
    Route::resource('payments', 'PaymentController');
    Route::patch('/payments/{id}/approve','PaymentController@approve');

});
