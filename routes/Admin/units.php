<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('units-list', 'unitsController@list')->name('units-list');
    Route::get('create-units', 'unitsController@create')->name('create-units');
    Route::post('units/store', 'unitsController@store')->name('save-units');
    Route::get('view-units/{id}', 'unitsController@view');
    Route::get('edit-units/{id}', 'unitsController@edit');
    Route::get('units-update-status/{id}', 'unitsController@updateStatus');
    Route::get('units/delete/{id}', 'unitsController@delete');
});
