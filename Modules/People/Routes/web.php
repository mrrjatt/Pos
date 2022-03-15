<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {

    //Customers
    Route::resource('customers', 'CustomersController');
    Route::get('customers/pay-veiw/{customer}', 'CustomersController@customerPayView')->name("customers.pay.view");
    Route::get('customers/payment-history/{customer}', 'CustomersController@customerPaymentHistory')->name("customers.payment.history");
    Route::get('customers/payment-history-view/{customer}', 'CustomersController@customerPaymentHistoryView')->name("customers.payment.history.view");
    Route::post('customers/pay/{customer}', 'CustomersController@customerPay')->name("customers.pay");
    //Suppliers
    Route::resource('suppliers', 'SuppliersController');

});
