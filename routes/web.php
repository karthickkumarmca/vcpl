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


Route::get('/', function () {
	return view('welcome');
});

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/dashboard', 'CommonController@dashboard')->name('dashboard');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['prefix' => 'master/','namespace'=>'master','middleware' => 'auth'], function () use ($router) {

	Route::group(['prefix' => 'categories/','middleware' => 'auth'], function () use ($router) {
		$router->get('list', 'categoriesController@list')->name('categories-list');
	    $router->get('create', 'categoriesController@create')->name('create-categories');
	    $router->post('store', 'categoriesController@store')->name('save-categories');
	    $router->get('view/{id}', 'categoriesController@view');
	    $router->get('edit/{id}', 'categoriesController@edit');
	    $router->get('update-status/{id}', 'categoriesController@updateStatus');
	    $router->get('delete/{id}', 'categoriesController@delete');
	});

	Route::group(['prefix' => 'sub-categories/','middleware' => 'auth'], function () use ($router) {
		$router->get('list', 'SubcategoriesController@list')->name('sub-categories-list');
	    $router->get('create', 'SubcategoriesController@create')->name('create-sub-categories');
	    $router->post('store', 'SubcategoriesController@store')->name('save-sub-categories');
	    $router->get('view/{id}', 'SubcategoriesController@view');
	    $router->get('edit/{id}', 'SubcategoriesController@edit');
	    $router->get('update-status/{id}', 'SubcategoriesController@updateStatus');
	    $router->get('delete/{id}', 'SubcategoriesController@delete');
	});

	Route::group(['prefix' => 'product-details/','middleware' => 'auth'], function () use ($router) {
		$router->get('list', 'ProductdetailsController@list')->name('product-details-list');
	    $router->get('create', 'ProductdetailsController@create')->name('create-product-details');
	    $router->post('store', 'ProductdetailsController@store')->name('save-product-details');
	    $router->get('view/{id}', 'ProductdetailsController@view');
	    $router->get('edit/{id}', 'ProductdetailsController@edit');
	    $router->get('update-status/{id}', 'ProductdetailsController@updateStatus');
	    $router->get('delete/{id}', 'ProductdetailsController@delete');
	});
    
});

require(__DIR__ . '/Admin/user.php');
require(__DIR__ . '/Admin/customer.php');
require(__DIR__ . '/Admin/units.php');
require(__DIR__ . '/Admin/clients.php');
require(__DIR__ . '/Admin/roles.php');
// require(__DIR__ . '/Admin/categories.php');
// require(__DIR__ . '/Admin/sub_categories.php');
require(__DIR__ . '/Admin/stock.php');


Route::get('/clear-cache', function () {
   Artisan::call('cache:clear');
   Artisan::call('route:clear');

   return "Cache cleared successfully";
});
