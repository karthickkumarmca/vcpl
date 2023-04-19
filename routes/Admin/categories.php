<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('categories-list', 'categoriesController@list')->name('categories-list');
    Route::get('create-categories', 'categoriesController@create')->name('create-categories');
    Route::post('categories/store', 'categoriesController@store')->name('save-categories');
    Route::get('view-categories/{id}', 'categoriesController@view');
    Route::get('edit-categories/{id}', 'categoriesController@edit');
    Route::get('categories-update-status/{id}', 'categoriesController@updateStatus');
    Route::get('categories/delete/{id}', 'categoriesController@delete');
});
