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

//Route::get('/', 'PagesController@index');
Route::get('/about', 'PagesController@about');
Route::get('/services', 'PagesController@services');

//Route::resource('/shoppingLists', 'ShoppingListsController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/preferences', 'HomeController@getPreferences')->name('preferences');
Route::post('/preferences', 'HomeController@postPreferences')->name('preferences.submit');

Route::resource('/', 'ItemsController');


