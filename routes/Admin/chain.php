<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('chain-list', 'ChainController@list')->name('chain-list');
    Route::get('create-chain', 'ChainController@create')->name('create-chain');
    Route::post('chain/store', 'ChainController@store')->name('save-chain');
    Route::get('view-chain/{id}', 'ChainController@view');
    Route::get('edit-chain/{id}', 'ChainController@edit');
    Route::get('chain-update-status/{id}', 'ChainController@updateStatus');
    Route::get('chain/delete/{id}', 'ChainController@delete');
});
