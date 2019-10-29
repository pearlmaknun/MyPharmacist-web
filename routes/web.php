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

Route::get('/admin', 'AdminController@index');

Route::resource('/admin/consultation', 'ConsultationController');
Route::resource('/admin/konseli', 'KonseliController');
Route::resource('/admin/apoteker', 'ApotekerController');
//Route::resource('/admin/import', 'ImportUserController');

Route::get('/admin/import', 'ImportUserController@index')->name('import');
Route::post('/admin/import', 'ImportUserController@import')->name('import.import');
// Route::post('import', 'TestController@import');
// Route::get('export', 'TestController@export');
