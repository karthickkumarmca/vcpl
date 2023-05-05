<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' =>  ['auth']], function () {
    Route::get('roles-list', 'RolesController@list')->name('roles-list');
    Route::get('create-roles', 'RolesController@create')->name('create-roles');
    Route::post('roles/store', 'RolesController@store')->name('save-roles');
    Route::get('view-roles/{id}', 'RolesController@view');
    Route::get('edit-roles/{id}', 'RolesController@edit');
    Route::get('roles-update-status/{id}', 'RolesController@updateStatus');
    Route::get('roles/delete/{id}', 'RolesController@delete');
});
