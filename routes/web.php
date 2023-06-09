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

Route::group(['prefix' => 'master/','namespace'=>'master','middleware' => ['auth']], function () use ($router) {


	Route::group(['prefix' => 'architect-site/'], function () use ($router) {
		$router->get('list', 'Architect_siteController@list')->name('architect-site-list');
	    $router->get('create', 'Architect_siteController@create')->name('create-architect-site');
	    $router->post('store', 'Architect_siteController@store')->name('save-architect-site');
	    $router->get('view/{id}', 'Architect_siteController@view');
	    $router->get('edit/{id}', 'Architect_siteController@edit');
	    $router->get('update-status/{id}', 'Architect_siteController@updateStatus');
	    $router->get('delete/{id}', 'Architect_siteController@delete');
	});

	Route::group(['prefix' => 'client-site/'], function () use ($router) {
		$router->get('list', 'Client_siteController@list')->name('client-info-list');
	    $router->get('create', 'Client_siteController@create')->name('create-client-site');
	    $router->post('store', 'Client_siteController@store')->name('save-client-site');
	    $router->get('view/{id}', 'Client_siteController@view');
	    $router->get('edit/{id}', 'Client_siteController@edit');
	    $router->get('update-status/{id}', 'Client_siteController@updateStatus');
	    $router->get('delete/{id}', 'Client_siteController@delete');
	});

	Route::group(['prefix' => 'categories/'], function () use ($router) {
		$router->get('list', 'CategoriesController@list')->name('categories-list');
	    $router->get('create', 'CategoriesController@create')->name('create-categories');
	    $router->post('store', 'CategoriesController@store')->name('save-categories');
	    $router->get('view/{id}', 'CategoriesController@view');
	    $router->get('edit/{id}', 'CategoriesController@edit');
	    $router->get('update-status/{id}', 'CategoriesController@updateStatus');
	    $router->get('delete/{id}', 'CategoriesController@delete');
	});

	Route::group(['prefix' => 'sub-categories/'], function () use ($router) {
		$router->get('list', 'SubcategoriesController@list')->name('sub-categories-list');
	    $router->get('create', 'SubcategoriesController@create')->name('create-sub-categories');
	    $router->post('store', 'SubcategoriesController@store')->name('save-sub-categories');
	    $router->get('view/{id}', 'SubcategoriesController@view');
	    $router->get('edit/{id}', 'SubcategoriesController@edit');
	    $router->get('update-status/{id}', 'SubcategoriesController@updateStatus');
	    $router->get('delete/{id}', 'SubcategoriesController@delete');
	});

	Route::group(['prefix' => 'product-details/'], function () use ($router) {
		$router->get('list', 'ProductdetailsController@list')->name('product-details-list');
	    $router->get('create', 'ProductdetailsController@create')->name('create-product-details');
	    $router->post('store', 'ProductdetailsController@store')->name('save-product-details');
	    $router->get('view/{id}', 'ProductdetailsController@view');
	    $router->get('edit/{id}', 'ProductdetailsController@edit');
	    $router->get('update-status/{id}', 'ProductdetailsController@updateStatus');
	    $router->post('get-sub-category', 'ProductdetailsController@getSubCategory');
	    $router->get('delete/{id}', 'ProductdetailsController@delete');
	});

	Route::group(['prefix' => 'labour-categories/'], function () use ($router) {
		$router->get('list', 'Labour_categoriesController@list')->name('labour-categories-list');
	    $router->get('create', 'Labour_categoriesController@create')->name('create-labour-categories');
	    $router->post('store', 'Labour_categoriesController@store')->name('save-labour-categories');
	    $router->get('view/{id}', 'Labour_categoriesController@view');
	    $router->get('edit/{id}', 'Labour_categoriesController@edit');
	    $router->get('update-status/{id}', 'Labour_categoriesController@updateStatus');
	    $router->get('delete/{id}', 'Labour_categoriesController@delete');
	});

	Route::group(['prefix' => 'labour-wages/'], function () use ($router) {
		$router->get('list', 'Labour_wagesController@list')->name('labour-wages-list');
	    $router->get('create', 'Labour_wagesController@create')->name('create-labour-wages');
	    $router->post('store', 'Labour_wagesController@store')->name('save-labour-wages');
	    $router->get('view/{id}', 'Labour_wagesController@view');
	    $router->get('edit/{id}', 'Labour_wagesController@edit');
	    $router->get('update-status/{id}', 'Labour_wagesController@updateStatus');
	    $router->get('delete/{id}', 'Labour_wagesController@delete');
	});

	Route::group(['prefix' => 'property-categories/'], function () use ($router) {
		$router->get('list', 'Property_categoriesController@list')->name('property-categories-list');
	    $router->get('create', 'Property_categoriesController@create')->name('create-property-categories');
	    $router->post('store', 'Property_categoriesController@store')->name('save-property-categories');
	    $router->get('view/{id}', 'Property_categoriesController@view');
	    $router->get('edit/{id}', 'Property_categoriesController@edit');
	    $router->get('update-status/{id}', 'Property_categoriesController@updateStatus');
	    $router->get('delete/{id}', 'Property_categoriesController@delete');
	});
    

    Route::group(['prefix' => 'ownership/'], function () use ($router) {
		$router->get('list', 'OwnershipController@list')->name('ownership-list');
	    $router->get('create', 'OwnershipController@create')->name('create-ownership');
	    $router->post('store', 'OwnershipController@store')->name('save-ownership');
	    $router->get('view/{id}', 'OwnershipController@view');
	    $router->get('edit/{id}', 'OwnershipController@edit');
	    $router->get('update-status/{id}', 'OwnershipController@updateStatus');
	    $router->get('delete/{id}', 'OwnershipController@delete');
	});

	Route::group(['prefix' => 'property-name/'], function () use ($router) {
		$router->get('list', 'PropertynameController@list')->name('property-name-list');
	    $router->get('create', 'PropertynameController@create')->name('create-property-name');
	    $router->post('store', 'PropertynameController@store')->name('save-property-name');
	    $router->get('view/{id}', 'PropertynameController@view');
	    $router->get('edit/{id}', 'PropertynameController@edit');
	    $router->get('update-status/{id}', 'PropertynameController@updateStatus');
	    $router->get('delete/{id}', 'PropertynameController@delete');
	});


	Route::group(['prefix' => 'centering-materials/'], function () use ($router) {
		$router->get('list', 'Centering_materialsController@list')->name('centering-materials-list');
	    $router->get('create', 'Centering_materialsController@create')->name('create-centering-materials');
	    $router->post('store', 'Centering_materialsController@store')->name('save-centering-materials');
	    $router->get('view/{id}', 'Centering_materialsController@view');
	    $router->get('edit/{id}', 'Centering_materialsController@edit');
	    $router->get('update-status/{id}', 'Centering_materialsController@updateStatus');
	    $router->get('delete/{id}', 'Centering_materialsController@delete');
	});

	Route::group(['prefix' => 'lorry-materials/'], function () use ($router) {
		$router->get('list', 'Lorry_materialsController@list')->name('lorry-materials-list');
	    $router->get('create', 'Lorry_materialsController@create')->name('create-lorry-materials');
	    $router->post('store', 'Lorry_materialsController@store')->name('save-lorry-materials');
	    $router->get('view/{id}', 'Lorry_materialsController@view');
	    $router->get('edit/{id}', 'Lorry_materialsController@edit');
	    $router->get('update-status/{id}', 'Lorry_materialsController@updateStatus');
	    $router->get('delete/{id}', 'Lorry_materialsController@delete');
	});

	Route::group(['prefix' => 'shop-materials/'], function () use ($router) {
		$router->get('list', 'Shop_materialsController@list')->name('shop-materials-list');
	    $router->get('create', 'Shop_materialsController@create')->name('create-shop-materials');
	    $router->post('store', 'Shop_materialsController@store')->name('save-shop-materials');
	    $router->get('view/{id}', 'Shop_materialsController@view');
	    $router->get('edit/{id}', 'Shop_materialsController@edit');
	    $router->get('update-status/{id}', 'Shop_materialsController@updateStatus');
	    $router->get('delete/{id}', 'Shop_materialsController@delete');
	});

	Route::group(['prefix' => 'toolsplants-materials/'], function () use ($router) {
		$router->get('list', 'Toolsplants_materialsController@list')->name('toolsplants-materials-list');
	    $router->get('create', 'Toolsplants_materialsController@create')->name('create-toolsplants-materials');
	    $router->post('store', 'Toolsplants_materialsController@store')->name('save-toolsplants-materials');
	    $router->get('view/{id}', 'Toolsplants_materialsController@view');
	    $router->get('edit/{id}', 'Toolsplants_materialsController@edit');
	    $router->get('update-status/{id}', 'Toolsplants_materialsController@updateStatus');
	    $router->get('delete/{id}', 'Toolsplants_materialsController@delete');
	});

	Route::group(['prefix' => 'vehicle-materials/'], function () use ($router) {
		$router->get('list', 'Vehicle_materialsController@list')->name('vehicle-materials-list');
	    $router->get('create', 'Vehicle_materialsController@create')->name('create-vehicle-materials');
	    $router->post('store', 'Vehicle_materialsController@store')->name('save-vehicle-materials');
	    $router->get('view/{id}', 'Vehicle_materialsController@view');
	    $router->get('edit/{id}', 'Vehicle_materialsController@edit');
	    $router->get('update-status/{id}', 'Vehicle_materialsController@updateStatus');
	    $router->get('delete/{id}', 'Vehicle_materialsController@delete');
	});

	Route::group(['prefix' => 'site-info/'], function () use ($router) {
		$router->get('list', 'SiteinfoController@list')->name('site-info-list');
	    $router->get('create', 'SiteinfoController@create')->name('create-site-info');
	    $router->post('store', 'SiteinfoController@store')->name('save-site-info');
	    $router->get('view/{id}', 'SiteinfoController@view');
	    $router->get('edit/{id}', 'SiteinfoController@edit');
	    $router->get('update-status/{id}', 'SiteinfoController@updateStatus');
	    $router->post('get-sub-category', 'SiteinfoController@getSubCategory');
	    $router->get('delete/{id}', 'SiteinfoController@delete');
	});

	Route::group(['prefix' => 'staffgroups/'], function () use ($router) {
		Route::get('list', 'StaffgroupsController@list')->name('staffgroups-list');
	    Route::get('create', 'StaffgroupsController@create')->name('create-staffgroups');
	    Route::post('store', 'StaffgroupsController@store')->name('save-staffgroups');
	    Route::get('view/{id}', 'StaffgroupsController@view');
	    Route::get('edit/{id}', 'StaffgroupsController@edit');
	    Route::get('update-status/{id}', 'StaffgroupsController@updateStatus');
	    Route::get('delete/{id}', 'StaffgroupsController@delete');

	});
	Route::group(['prefix' => 'staff-details/'], function () use ($router) {
		Route::get('list', 'StaffdetailsController@list')->name('staff-details-list');
	    Route::get('create', 'StaffdetailsController@create')->name('create-staff-details');
	    Route::post('store', 'StaffdetailsController@store')->name('save-staff-details');
	    Route::get('view/{id}', 'StaffdetailsController@view');
	    Route::get('edit/{id}', 'StaffdetailsController@edit');
	    Route::get('update-status/{id}', 'StaffdetailsController@updateStatus');
	    Route::get('delete/{id}', 'StaffdetailsController@delete');
	});

	Route::group(['prefix' => 'product-rental/'], function () use ($router) {
		$router->get('list', 'ProductrentalController@list')->name('product-rental-list');
	    $router->get('create', 'ProductrentalController@create')->name('create-product-rental');
	    $router->post('store', 'ProductrentalController@store')->name('save-product-rental');
	    $router->get('view/{id}', 'ProductrentalController@view');
	    $router->get('edit/{id}', 'ProductrentalController@edit');
	    $router->get('update-status/{id}', 'ProductrentalController@updateStatus');
	    $router->post('get-product-list', 'ProductrentalController@getProductList');
	    $router->get('delete/{id}', 'ProductrentalController@delete');
	});

	Route::group(['prefix' => 'message-header/'], function () use ($router) {
		$router->get('list', 'MessageheaderController@list')->name('message-header-list');
	    $router->get('create', 'MessageheaderController@create')->name('create-message-header');
	    $router->post('store', 'MessageheaderController@store')->name('save-message-header');
	    $router->get('view/{id}', 'MessageheaderController@view');
	    $router->get('edit/{id}', 'MessageheaderController@edit');
	    $router->get('update-status/{id}', 'MessageheaderController@updateStatus');
	    $router->post('get-product-list', 'MessageheaderController@getProductList');
	    $router->get('delete/{id}', 'MessageheaderController@delete');
	});

	Route::group(['prefix' => 'rental-agreement/'], function () use ($router) {
		$router->get('list', 'Rental_agreementController@list')->name('rental-agreement-list');
	    $router->get('create', 'Rental_agreementController@create')->name('create-rental-agreement');
	    $router->post('store', 'Rental_agreementController@store')->name('save-rental-agreement');
	    $router->get('view/{id}', 'Rental_agreementController@view');
	    $router->get('edit/{id}', 'Rental_agreementController@edit');
	    $router->get('update-status/{id}', 'Rental_agreementController@updateStatus');
	    $router->post('get-product-list', 'Rental_agreementController@getProductList');
	    $router->post('getname', 'Rental_agreementController@getname');
	    $router->get('delete/{id}', 'Rental_agreementController@delete');
	});

	/*Route::group(['prefix' => 'staff-details/','middleware' => 'auth'], function () use ($router) {

		Route::get('roles-list', 'RolesController@list')->name('roles-list');
    	Route::get('create-roles', 'RolesController@create')->name('create-roles');
    	Route::post('roles/store', 'RolesController@store')->name('save-roles');
    	Route::get('view-roles/{id}', 'RolesController@view');
    	Route::get('edit-roles/{id}', 'RolesController@edit');
    	Route::get('roles-update-status/{id}', 'RolesController@updateStatus');
    Route::get('roles/delete/{id}', 'RolesController@delete');

	});*/
	//roles-list
});


require(__DIR__ . '/Admin/user.php');
require(__DIR__ . '/Admin/customer.php');
require(__DIR__ . '/Admin/units.php');
require(__DIR__ . '/Admin/clients.php');
//require(__DIR__ . '/Admin/staff_details.php');
//require(__DIR__ . '/Admin/staffgroups.php');
require(__DIR__ . '/Admin/roles.php');
// require(__DIR__ . '/Admin/categories.php');
// require(__DIR__ . '/Admin/sub_categories.php');
require(__DIR__ . '/Admin/stock.php');
require(__DIR__ . '/appview/web.php');


Route::get('/clear-cache', function () {
   Artisan::call('cache:clear');
   Artisan::call('route:clear');

   return "Cache cleared successfully";
});

Route::get('/migrate-db', function () {
   Artisan::call('migrate');

   return "migrate successfully";
});
