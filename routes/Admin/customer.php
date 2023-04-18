<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('customer-list', 'CustomerController@list')->name('customer-list');
    Route::get('create-customer', 'CustomerController@create')->name('create-customer');
    Route::post('customer/store', 'CustomerController@store')->name('save-customer');
    Route::get('view-customer/{id}', 'CustomerController@view');
    Route::get('edit-customer/{id}', 'CustomerController@edit');
    Route::get('customer-update-status/{id}', 'CustomerController@updateStatus');
    Route::get('customer/delete/{id}', 'CustomerController@delete');
});
