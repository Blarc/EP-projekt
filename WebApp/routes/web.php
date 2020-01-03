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

// for updating and creating seller accounts
Route::get('/seller-preferences/{id}', 'HomeController@viewManagedProfile')->name('manageSeller');
Route::post('/seller-preferences/{id}', 'HomeController@editManagedProfile')->name('manageSeller.submit');
Route::get('/seller-create', 'HomeController@viewCreateForm')->name('createSeller');
Route::post('/seller-create', 'HomeController@createManagedProfile')->name('createSeller.post');

// for updating and creating customer accounts
Route::get('/customer-preferences/{id}', 'HomeController@viewManagedProfile')->name('manageCustomer');
Route::post('/customer-preferences/{id}', 'HomeController@editManagedProfile')->name('manageCustomer.submit');
Route::get('/customer-create', 'HomeController@createManagedProfile')->name('createCustomer');
Route::get('/item-manage', 'ItemsController@sellerindex')->name('manageItems');
Route::get('/item-create', 'HomeController@createItem')->name('createItem');
Route::get('/item-show/{id}', 'ItemsController@sellershow')->name('showItem');
Route::get('/item/{id}/edit', 'ItemsController@selleredit')->name('editItem');
Route::get('/customer-create', 'HomeController@viewCreateForm')->name('createCustomer');
Route::post('/customer-create', 'HomeController@createManagedProfile')->name('createCustomer.post');
Route::get('/seller/shoppingLists', 'ShoppingListsController@index')->name('indexSL');
Route::get('/seller/sl/{id}/delete', 'ShoppingListsController@destroy')->name('deleteSL');
Route::get('/seller/item/{id}/delete', 'ItemsController@destroy')->name('deleteItem');
Route::get('/seller/sl/{id}/accept', 'ShoppingListsController@accept')->name('acceptSL');
Route::get('/seller/sl/{id}/stornate', 'ShoppingListsController@stornate')->name('stornateSL');


Route::resource('/', 'ItemsController');


