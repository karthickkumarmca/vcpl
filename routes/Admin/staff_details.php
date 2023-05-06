<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' =>  ['auth']], function () {
    Route::get('staff-details-list', 'StaffdetailsController@list')->name('staff-details-list');
    Route::get('create-staff-details', 'StaffdetailsController@create')->name('create-staff-details');
    Route::post('staff-details/store', 'StaffdetailsController@store')->name('save-staff-details');
    Route::get('view-staff-details/{id}', 'StaffdetailsController@view');
    Route::get('edit-staff-details/{id}', 'StaffdetailsController@edit');
    Route::get('staff-details-update-status/{id}', 'StaffdetailsController@updateStatus');
    Route::get('staff-details/delete/{id}', 'StaffdetailsController@delete');
});
