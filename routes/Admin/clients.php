<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('clients-list', 'ClientsController@list')->name('clients-list');
    Route::get('create-clients', 'ClientsController@create')->name('create-clients');
    Route::post('clients/store', 'ClientsController@store')->name('save-clients');
    Route::get('view-clients/{id}', 'ClientsController@view');
    Route::get('edit-clients/{id}', 'ClientsController@edit');
    Route::get('clients-update-status/{id}', 'ClientsController@updateStatus');
    Route::get('clients/delete/{id}', 'ClientsController@delete');
});
