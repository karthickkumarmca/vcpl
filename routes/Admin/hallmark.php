<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('hallmark-list', 'HallmarkController@list')->name('hallmark-list');
    Route::get('create-hallmark', 'HallmarkController@create')->name('create-hallmark');
    Route::post('hallmark/store', 'HallmarkController@store')->name('save-hallmark');
    Route::get('view-hallmark/{id}', 'HallmarkController@view');
    Route::get('edit-hallmark/{id}', 'HallmarkController@edit');
    Route::get('hallmark-update-status/{id}', 'HallmarkController@updateStatus');
    Route::get('hallmark/delete/{id}', 'HallmarkController@delete');
});
