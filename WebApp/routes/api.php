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

Route::post('register', 'Auth\ApiRegisterController@register');
Route::post('login', 'Auth\ApiLoginController@login');
Route::post('logout', 'Auth\ApiLoginController@logout');

Route::group(['middleware' => 'auth:api'], function () {
    // ITEMS
//    Route::post('items', 'ItemsController@store');
    Route::put('items/{id}', 'ItemsController@update');
    Route::delete('items/{id}', 'ItemsController@destroy');

    // SHOPPING LISTS
    Route::post('shoppingLists', 'ShoppingListsController@store');
    Route::put('shoppingLists/{id}', 'ShoppingListsController@update');
    Route::delete('shoppingLists/{id}', 'ShoppingListsController@destroy');
});

Route::get('items', 'ItemsController@index');
Route::get('items/{id}', 'ItemsController@show');
Route::post('items', 'ItemsController@store');

// SHOPPING LISTS
Route::get('shoppingLists', 'ShoppingListsController@index');
Route::get('shoppingLists/{id}', 'ShoppingListsController@show');
