<?php

use Illuminate\Support\Facades\Route;

Route::get("users/otp", 'ImmigrantController@getOtp');

Route::group(['middleware' =>  ['auth']], function () {
    // Route::get('/', 'AdminController@index')->name('home');
    Route::get('/', function () {
        switch (Session::get('user_role')) {
            case "Super Admin":
            return redirect()->route('dashboard');
            break;

            default:
            return redirect()->route('dashboard');
            break;
        }
    })->name('home');

    Route::get('user-list', 'UserController@list')->name('user-list');
    Route::get('user-list-test', 'UserController@list_test');
    Route::get('create-user', 'UserController@create')->name('create-user');
    Route::post('user/store', 'UserController@store')->name('save-user');
    Route::get('view-user/{id}', 'UserController@view');
    Route::get('edit-user/{id}', 'UserController@edit');
    Route::get('change-password/{id}/{userrole}', 'UserController@changeUserPassword');
    Route::post('change-password', 'UserController@changeUserPassword');
    Route::get('change-user-password', 'UserController@changePassword');
    Route::post('change-staff-password', 'UserController@changeStaffPassword');
    Route::get('change-staff-password/{id}', 'UserController@changeStaffPassword');
    Route::post('change-user-password', 'UserController@changePassword');
    Route::get('update-status/{id}', 'UserController@updateStatus');
    Route::get('user/delete/{id}', 'UserController@delete');
});
