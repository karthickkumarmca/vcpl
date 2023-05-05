<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' =>  ['auth']], function () {
    Route::get('staffgroups-list', 'StaffgroupsController@list')->name('staffgroups-list');
    Route::get('create-staffgroups', 'StaffgroupsController@create')->name('create-staffgroups');
    Route::post('staffgroups/store', 'StaffgroupsController@store')->name('save-staffgroups');
    Route::get('view-staffgroups/{id}', 'StaffgroupsController@view');
    Route::get('edit-staffgroups/{id}', 'StaffgroupsController@edit');
    Route::get('staffgroups-update-status/{id}', 'StaffgroupsController@updateStatus');
    Route::get('staffgroups/delete/{id}', 'StaffgroupsController@delete');
});
