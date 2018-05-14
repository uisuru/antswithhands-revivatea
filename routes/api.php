<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'cors', 'prefix' => '/v1'], function () {
    //Route::group(['namespace' => '\Api'], function() {
        Route::post('/login', 'RestController@authenticate');
        Route::post('/register', 'RestController@register');
        Route::get('/logout/{api_token}', 'RestController@logout');

        Route::get('/products', 'RestController@getProducts');
        Route::get('/customers', 'RestController@getCustomers');
        Route::get('/packages', 'RestController@getPackages');
        Route::get('/invoices', 'RestController@getInvoices');
        Route::get('/invoice_items', 'RestController@getInvoiceItems');
        Route::get('/getRoutes', 'RestController@getRoutes');
        Route::get('/getCustomersByRouteId', 'RestController@getCustomersByRouteId');
        Route::get('/getInvoicesByCustomer', 'RestController@getInvoicesByCustomer');
        Route::get('/getPaymentMethods', 'RestController@getPaymentMethods');

        Route::post('/invoices', 'RestController@saveInvoices');
        Route::post('/customers', 'RestController@saveCustomers');
        Route::post('/payments', 'RestController@savePayments');
    //});
});
