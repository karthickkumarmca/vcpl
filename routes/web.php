<?php

use Illuminate\Support\Facades\Route;

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

require(__DIR__ . '/Admin/user.php');
require(__DIR__ . '/Admin/customer.php');
require(__DIR__ . '/Admin/units.php');
require(__DIR__ . '/Admin/chain.php');
require(__DIR__ . '/Admin/stock.php');
