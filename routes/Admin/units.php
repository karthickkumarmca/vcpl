<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('units-list', 'UnitsController@list')->name('units-list');
    Route::get('create-units', 'UnitsController@create')->name('create-units');
    Route::post('units/store', 'UnitsController@store')->name('save-units');
    Route::get('view-units/{id}', 'UnitsController@view');
    Route::get('edit-units/{id}', 'UnitsController@edit');
    Route::get('units-update-status/{id}', 'UnitsController@updateStatus');
    Route::get('units/delete/{id}', 'UnitsController@delete');
});
