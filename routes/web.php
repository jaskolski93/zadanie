<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/sales/report', 'SalesController@report')->name('sales.report');
Route::post('/sales/report', 'SalesController@report')->name('sales.report');
Route::get('/user/password', 'UserController@password')->name('user.password');
Route::get('/sales/chart', 'SalesController@chart')->name('sales.chart');
