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
//Route::get('/about', 'PagesController@about');
//Route::get('/services', 'PagesController@services');

//Route::resource('/shoppingLists', 'ShoppingListsController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/edit-profile', 'HomeController@getEditProfile')->middleware('auth.x509')->name('edit-profile');
Route::post('/edit-profile', 'HomeController@postEditProfile')->middleware('auth.x509')->name('edit-profile.submit');

// for updating and creating seller accounts
Route::get('/seller-edit-profile/{id}', 'HomeController@viewManagedProfile')->middleware('auth.x509')->name('manageSeller');
Route::post('/seller-edit-profile/{id}', 'HomeController@editManagedProfile')->middleware('auth.x509')->name('manageSeller.submit');
Route::get('/seller-create', 'HomeController@viewCreateForm')->middleware('auth.x509')->name('createSeller');
Route::post('/seller-create', 'HomeController@createManagedProfile')->middleware('auth.x509')->name('createSeller.post');

// for updating and creating customer accounts
Route::get('/customer-edit-profile/{id}', 'HomeController@viewManagedProfile')->name('manageCustomer');
Route::post('/customer-edit-profile/{id}', 'HomeController@editManagedProfile')->name('manageCustomer.submit');
// Route::get('/customer-create', 'HomeController@createManagedProfile')->name('createCustomer');

// for making user accounts active/inactive
Route::get('/changeProfileStatus/{id}', 'HomeController@changeProfileStatus')->middleware('auth.x509')->name('changeProfileStatus');

Route::get('/item-manage', 'ItemsController@sellerindex')->middleware('auth.x509')->name('manageItems');
Route::get('/item-deactivated', 'ItemsController@sellerindexDeactivated')->middleware('auth.x509')->name('manageDeactivatedItems');
Route::get('/item-create', 'HomeController@viewCreateItemForm')->middleware('auth.x509')->name('createItem');
Route::post('/item-create', 'HomeController@createItem')->middleware('auth.x509')->name('createItem.post');
Route::post('/seller/edit-item/{id}', 'HomeController@editItemSeller')->middleware('auth.x509')->name('sellerItem.submit');
Route::get('/item-show/{id}', 'ItemsController@sellershow')->middleware('auth.x509')->name('showItem');
Route::get('/seller/item/{id}/edit', 'ItemsController@selleredit')->middleware('auth.x509')->name('editItem');
Route::get('/customer-create', 'HomeController@viewCreateForm')->middleware('auth.x509')->name('createCustomer');
Route::post('/customer-create', 'HomeController@createManagedProfile')->middleware('auth.x509')->name('createCustomer.post');
Route::get('/seller/shoppingLists', 'ShoppingListsController@index')->middleware('auth.x509')->name('indexSL');
Route::get('/seller/sl/{id}/delete', 'ShoppingListsController@destroy')->middleware('auth.x509')->name('deleteSL');
Route::get('/seller/item/{id}/delete', 'ItemsController@destroy')->middleware('auth.x509')->name('deleteItem');
Route::get('/seller/item/{id}/activate', 'ItemsController@activate')->middleware('auth.x509')->name('activateItem');
Route::get('/seller/sl/{id}/accept', 'ShoppingListsController@accept')->middleware('auth.x509')->name('acceptSL');
Route::get('/seller/sl/{id}/stornate', 'ShoppingListsController@stornate')->middleware('auth.x509')->name('stornateSL');
Route::get('/seller/sl-show/{id}', 'ShoppingListsController@slSellerShow')->middleware('auth.x509')->name('showSL');
// Route::get('/seller/sl-show/{id}', 'ShoppingListsController@slSellerShow')->name('showSL');

// Route::get('/shop', 'ItemsController@shopItems')->name('shop');
Route::get('/shop/item-show/{id}', 'ItemsController@shopShow')->name('shopShowItem');
Route::get('/shop/shoppingLists', 'HomeController@shoppingListsShow')->name('shoppingListsShow');
Route::get('/shop/shoppingLists/{id}', 'ShoppingListsController@slShopShow')->name('shopShowSL');
// Route::get('/shop/baskets', 'HomeController@slShopShowBaskets')->name('shopBaskets');
Route::get('/shop/sl/{id}/checkout', 'ShoppingListsController@checkout')->name('checkoutSL');
Route::post('/shoppingList-create/{id}', 'HomeController@createShoppingList')->name('createShoppingList.post');
Route::get('/shop/add/{id}', 'ItemsController@toBasket')->name('toBasket');
Route::get('/shop/{slid}', 'ItemsController@addItemShop')->name('addItemShop');
//Route::get('/shop/{slid}/{iid}', 'ItemsController@addItemShop')->name('addItemShop');
Route::get('/shop/delete/{slid}/{iid}', 'HomeController@deleteItemShoppingList')->name('deleteItemShoppingList');
Route::post('/shoppingList/amount/{slid}/{iid}', 'HomeController@setAmountShoppingList')->name('setAmountShoppingList.post');


Route::resource('/', 'ItemsController');


