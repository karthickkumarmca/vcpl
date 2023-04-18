<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('stock-list', 'StockController@list')->name('stock-list');
    Route::get('create-stock', 'StockController@create')->name('create-stock');
    Route::post('stock/store', 'StockController@store')->name('save-stock');
    Route::get('view-stock/{id}', 'StockController@view');
    Route::get('edit-stock/{id}', 'StockController@edit');
    Route::get('stock-update-status/{id}', 'StockController@updateStatus');
    Route::get('stock/delete/{id}', 'StockController@delete');
});
