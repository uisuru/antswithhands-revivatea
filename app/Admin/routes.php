<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('auth/users', 'UserController');

    $router->resource('auth/currency', 'CurrencyController');
    $router->resource('auth/pricelists', 'PriceListsController');
    $router->resource('auth/tax', 'TaxController');
    $router->resource('auth/paymentmethods', 'PaymentMethodsController');
    $router->resource('auth/paymentterms', 'PaymentTermsController');
    $router->resource('auth/stockadjustmentreasons', 'StockAdjustmentReasonsController');
    $router->resource('auth/company', 'CompanyController');
    $router->resource('auth/warehouse', 'WarehouseController');
    $router->resource('auth/suppliers', 'SuppliersController');

    $router->resource('auth/producttypes', 'ProductTypesController');
    $router->resource('auth/brands', 'BrandsController');
    $router->resource('auth/category', 'CategoryController');
    $router->resource('auth/products', 'ProductController');
    $router->resource('auth/route', 'RouteController');

    $router->resource('auth/grn', 'GrnController');
    $router->post('/auth/grn/create', 'GrnController@submitPost')->name('post-grn');
    $router->get('/auth/grn/items/{id}', 'GrnController@items');

    $router->get('auth/bincard', 'ReportController@getBincard');
    $router->post('auth/bincard/create', 'ReportController@postBincard')->name('post-bincard');
    $router->get('auth/stock', 'ReportController@getStock');
    $router->post('auth/stock/create', 'ReportController@postStock')->name('post-stock');
    $router->resource('auth/stock_transfer', 'StockTransferController');

    $router->resource('auth/packages', 'PackagesController');
    $router->resource('auth/core_configuration', 'CoreConfigurationController');
    $router->resource('auth/invoices', 'InvoiceController');

    /*$router->get('auth/grn', 'GrnController@index');
    $router->post('/auth/grn/create', 'GrnController@submitPost')->name('post-grn');*/


});
