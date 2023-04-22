<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('clients-list', 'clientsController@list')->name('clients-list');
    Route::get('create-clients', 'clientsController@create')->name('create-clients');
    Route::post('clients/store', 'clientsController@store')->name('save-clients');
    Route::get('view-clients/{id}', 'clientsController@view');
    Route::get('edit-clients/{id}', 'clientsController@edit');
    Route::get('clients-update-status/{id}', 'clientsController@updateStatus');
    Route::get('clients/delete/{id}', 'clientsController@delete');
});
