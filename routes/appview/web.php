<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::group(['prefix' => 'appview/','namespace'=>'appview','middleware' => ['auth']], function () use ($router) {


	Route::group(['prefix' => 'cement-movement/'], function () use ($router) {
	    $router->get('create', 'MaterialsController@cement_movement')->name('create-cement-movement');
	    $router->post('store', 'MaterialsController@cement_store');
	});
	Route::group(['prefix' => 'shop-movement/'], function () use ($router) {
	    $router->get('create', 'MaterialsController@shop_movement')->name('create-shop-movement');
	});

	Route::group(['prefix' => 'lorry-movement/'], function () use ($router) {
	    $router->get('create', 'MaterialsController@lorry_movement')->name('create-lorry-movement');
	    $router->post('store', 'MaterialsController@lorry_store')->name('create-lorry-store');
	});
	Route::group(['prefix' => 'labour-movement/'], function () use ($router) {
	    $router->get('create', 'MaterialsController@labour_movement')->name('create-labour-movement');
	    $router->post('store', 'MaterialsController@labour_store')->name('create-labour-store');
	});
	Route::group(['prefix' => 'workout-movement/'], function () use ($router) {
	    $router->get('create', 'MaterialsController@workout_movement')->name('create-workout-movement');
	});
	Route::group(['prefix' => 'centering-movement/'], function () use ($router) {
	    $router->get('create', 'MaterialsController@centering_movement')->name('create-centering-movement');
	});

	Route::group(['prefix' => 'tools-movement/'], function () use ($router) {
	    $router->get('create', 'MaterialsController@tools_movement')->name('create-tools-movement');
	});

	Route::group(['prefix' => 'task-movement/'], function () use ($router) {
	    $router->get('create', 'MaterialsController@task_movement')->name('create-task-movement');
	});

	
});


