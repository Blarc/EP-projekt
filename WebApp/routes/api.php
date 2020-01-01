<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// REGISTRATION & LOGIN
Route::post('register', 'Auth\ApiRegisterController@register');
Route::post('login', 'Auth\ApiLoginController@login');
Route::post('logout', 'Auth\ApiLoginController@logout');

// ITEMS (public)
Route::get('items', 'ItemsController@getAll');
Route::get('items/{id}', 'ItemsController@get');
Route::post('items', 'ItemsController@post');

// SHOPPING LISTS (public)
Route::get('shoppingLists', 'ShoppingListsController@index');
Route::get('shoppingLists/{id}', 'ShoppingListsController@show');

// USERS (TODO will not be public)
Route::get('users', 'UsersController@getAll');
Route::get('users/{id}', 'UsersController@get');
Route::put('users/{id}', 'UsersController@put');

Route::group(['middleware' => 'auth:api'], function () {
    // ITEMS (locked)
    // Route::post('items', 'ItemsController@store');
    Route::put('items/{id}', 'ItemsController@put');
    Route::delete('items/{id}', 'ItemsController@delete');

    // SHOPPING LISTS (locked)
    Route::post('shoppingLists', 'ShoppingListsController@store');
    Route::put('shoppingLists/{id}', 'ShoppingListsController@update');
    Route::delete('shoppingLists/{id}', 'ShoppingListsController@destroy');
});
