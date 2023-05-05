<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' =>  ['auth']], function () {
    Route::get('sub-categories-list', 'SubcategoriesController@list')->name('sub-categories-list');
    Route::get('create-sub-categories', 'SubcategoriesController@create')->name('create-sub-categories');
    Route::post('sub-categories/store', 'SubcategoriesController@store')->name('save-sub-categories');
    Route::get('view-sub-categories/{id}', 'SubcategoriesController@view');
    Route::get('edit-sub-categories/{id}', 'SubcategoriesController@edit');
    Route::get('sub-categories-update-status/{id}', 'SubcategoriesController@updateStatus');
    Route::get('sub-categories/delete/{id}', 'SubcategoriesController@delete');
});
