<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' =>  ['auth']], function () {
    Route::get('roles-list', 'rolesController@list')->name('roles-list');
    Route::get('create-roles', 'rolesController@create')->name('create-roles');
    Route::post('roles/store', 'rolesController@store')->name('save-roles');
    Route::get('view-roles/{id}', 'rolesController@view');
    Route::get('edit-roles/{id}', 'rolesController@edit');
    Route::get('roles-update-status/{id}', 'rolesController@updateStatus');
    Route::get('roles/delete/{id}', 'rolesController@delete');
});
