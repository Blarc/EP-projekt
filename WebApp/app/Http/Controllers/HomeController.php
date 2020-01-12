<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShoppingListsDetailsResource;
use App\ShoppingList;
use App\User;
use App\Item;
use App\Address;
use Auth;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AddressesController;
use App\Http\Controllers\ItemsController;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->active) {
            if ($user->hasPermissionTo('viewAdminHome')) {
                return view('admin.home');
            }

            if ($user->hasPermissionTo('viewCustomerHome')) {
                $user = auth()->user();
                $sls = $user->shoppingLists;
                return view('customer.home')->with('shoppingLists', $sls);
            }

            if ($user->hasPermissionTo('viewSellerHome')) {
                return view('seller.home');
            }

            return view('welcome');
        }

        else {
            Auth::logout();
            return redirect()->back()->with('error', 'Can\'t login.');
        }

    }

    // edit-profile of current user
    public function getEditProfile() {

        $user = auth()->user();
        $address = (new AddressesController)->get($user->address_id);

        if ($user->hasPermissionTo('viewAdminEditProfile')) {
            return view('admin.edit-profile');
        }

        if ($user->hasPermissionTo('viewCustomerEditProfile')) {
            return view('customer.edit-profile')->with('address', $address);
        }

        if ($user->hasPermissionTo('viewSellerEditProfile')) {
            return view('seller.edit-profile');
        }
    }

    public function postEditProfile(Request $request) {

        $user = auth()->user();

        $u = User::where('email' , $request->input('email'))->first();
        if ($u !== null && $u->email != $user->email) {
            return redirect()->back()->with('error', 'Email already exists!');
        }
        $response = ($user && $user->role == 'admin' || $user && $user->role == 'seller' ?
            (new UsersController)->putAdminOrSeller($request, $user->id) : (new UsersController)->put($request, $user->id));

        if (!isset($response->id)) {
            return redirect()->back()->with('error', $response->original);
        }
        return redirect()->back()->with('success', 'Profile updated successfully');

    }

    public function viewManagedProfile(Request $request, $id) {

        $user = auth()->user();

        if ($user && $user->role == 'admin') {
            $seller = $user->sellers->find($id);
            return view('admin.seller-edit-profile')->with('seller', $seller);
        }

        if ($user && $user->role == 'seller') {
            $customer = $user->customers->find($id);
            $address = $customer->address->find($customer->address_id);
            $data = [
                'address'  => $address,
                'customer' => $customer,
            ];
            return view('seller.customer-edit-profile')->with($data);
        }

    }

    public function editManagedProfile(Request $request, $id) {

        $user = auth()->user();

        if ($user && $user->role == 'admin') {
            $seller = $user->sellers->find($id);
            $u = User::where('email' , $request->input('email'))->first();
            if ($u !== null && $u->email != $seller->email) {
                return redirect()->back()->with('error', 'Email already exists!');
            }

            $response = (new UsersController)->putAdminOrSeller($request, $seller->id);
        }

        else {
            $customer = $user->customers->find($id);
            $u = User::where('email' , $request->input('email'))->first();
            if ($u !== null && $u->email != $customer->email) {
                return redirect()->back()->with('error', 'Email already exists!');
            }
            $response = (new UsersController)->put($request, $customer->id);
        }

        //dd($response);
        if (!isset($response->id)) {
            return redirect()->back()->with('error', $response->original);
        }
        return redirect()->back()->with('success', 'Profile updated successfully');

    }

    public function viewCreateForm() {

        $user = auth()->user();

        if ($user && $user->role == 'admin') {
            return view('admin.create-form');
        }

        else if ($user->role == 'seller') {
            return view('seller.create-form');
        }

    }

    public function createManagedProfile(Request $request) {

        $user = auth()->user();

        if ($user && $user->role == 'admin') {

            $u = User::where('email' , $request->input('email'))->first();
            if ($u !== null) {
                return redirect()->back()
                    ->with('error', 'Email already exists!')
                    ->withInput($request->input());
            }

            $seller = User::create([
                'firstName' => filter_var($request->input('firstName'), FILTER_SANITIZE_SPECIAL_CHARS),
                'lastName' => filter_var($request->input('lastName'), FILTER_SANITIZE_SPECIAL_CHARS),
                'email' => filter_var($request->input('email'), FILTER_SANITIZE_SPECIAL_CHARS),
                'telephone' => "",
                'password' => filter_var(bcrypt($request['password']), FILTER_SANITIZE_SPECIAL_CHARS),
                'role' => 'seller',
                'active' => true,
            ]);

            $seller->assignRole('seller');
            $seller->generateToken();
            $seller->save();
            $user->sellers()->attach($seller);

            return redirect()->intended('/home')->with('success', 'Seller created successfully');

        }

        else if ($user->role == 'seller') {

            $u = User::where('email' , $request->input('email'))->first();
            if ($u !== null) {
                return redirect()->back()
                    ->with('error', 'Email already exists!')
                    ->withInput($request->input());
            }

            $customer = User::create([
                'firstName' => filter_var($request->input('firstName'), FILTER_SANITIZE_SPECIAL_CHARS),
                'lastName' => filter_var($request->input('lastName'), FILTER_SANITIZE_SPECIAL_CHARS),
                'email' => filter_var($request->input('email'), FILTER_SANITIZE_SPECIAL_CHARS),
                'telephone' => filter_var($request->input('telephone'), FILTER_SANITIZE_SPECIAL_CHARS),
                'password' => filter_var(bcrypt($request['password']), FILTER_SANITIZE_SPECIAL_CHARS),
                'role' => 'customer',
                'active' => true,
            ]);
            $customer->assignRole('customer');
            $customer->generateToken();

            $address = Address::create([
                'street' => filter_var($request->input('street'), FILTER_SANITIZE_SPECIAL_CHARS),
                'post' => filter_var($request->input('post'), FILTER_SANITIZE_SPECIAL_CHARS),
                'postCode' => filter_var($request->input('postCode'), FILTER_SANITIZE_SPECIAL_CHARS)
            ]);

            $customer->address()->associate($address);
            $customer->save();
            $user->customers()->attach($customer);

            return redirect()->intended('/home')->with('success', 'Customer created successfully');
        }

        return redirect()->intended('/home')->with('error', 'Error occured when creating new profile!');

    }

    public function changeProfileStatus($id) {

        $user = auth()->user();

        if ($user && $user->role == 'admin' || $user->role == 'seller'){
            $profile = ($user->role == 'admin' ? $user->sellers->find($id) : $user->customers->find($id));
            $profile->active = !$profile->active;
            $profile->save();
        }

        return redirect()->intended('/home');

    }

    public function editItemSeller(Request $request, $id){

        $user = auth()->user();
//        dd($user);
        if ($user && $user->role == 'seller') {
            $item = Item::find($id);
            $response = (new ItemsController)->put($request, $item->id);

            //dd($response);
            if (!isset($response->id)) {
                return redirect()->back()->with('error', $response->original);
            }
            return redirect()->intended('/item-manage')->with('success', 'Item updated successfully');
        }

        return redirect('login')->with('warning', 'Unauthorized request');
    }

    public function createItem(Request $request){

        $user = auth()->user();

        if ($user && $user->role == 'seller') {
            $item = Item::create([
                'name' => filter_var($request->input('name'), FILTER_SANITIZE_SPECIAL_CHARS),
                'description' => filter_var($request->input('description'), FILTER_SANITIZE_SPECIAL_CHARS),
                'price' => filter_var($request->input('price'), FILTER_SANITIZE_SPECIAL_CHARS),
                'active' => '1'
            ]);
            $item->save();
            return redirect()->intended('/item-manage')->with('success', 'Item created successfully');
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

    }

    public function createShoppingList(Request $request, $id){
        $user = auth()->user();

        if ($user && $user->role == 'customer') {
            $sl = ShoppingList::create([
                'name' => filter_var($request['name'], FILTER_SANITIZE_SPECIAL_CHARS),
                'status' => '3',
                'user_id' => $user->id
            ]);
            $sl->save();
            return redirect()->back()->with('success', 'Shopping list created successfully');
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

    }

    public function viewCreateItemForm(){

        $user = auth()->user();
        if ($user && $user->role == 'seller') {
            return view('seller.create-item');
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');
    }

    public function shoppingListsShow(){

        $user = auth()->user();

        if ($user && $user->role == 'customer') {
            $sls = $user->shoppingLists;
            return view('customer.shopping-lists')->with('shoppingLists', $sls);
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

    }

    public function accept($id)
    {
        $user = auth()->user();

        if ($user && $user->role == 'seller') {
            DB::table('shopping_lists')->where('id', $id)->update(['status' => '1']);
            return redirect('/seller/shoppingLists');
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

    }

    public function setAmountShoppingList(Request $request, $slid, $iid){

        $user = auth()->user();

        if ($user && $user->role == 'customer') {
            $shoppingList = ShoppingList::find($slid);
            // $items_amount = $shoppingList->items()->where('item_id', $iid)->first()->pivot->items_amount;
            $shoppingList->items()->updateExistingPivot($iid, array('items_amount' => $request['items_amount']));
            $shoppingList->save();
            // return redirect('/seller/shoppingLists');
            return redirect()->back()->with('success', 'Shopping list updated successfully');
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

    }


    public function deleteItemShoppingList($slid, $iid){

        $user = auth()->user();

        if ($user && $user->role == 'customer') {
            $shoppingList = ShoppingList::find($slid);
            $shoppingList->items()->detach($iid);
            $shoppingList->save();
            return redirect()->back()->with('success', 'Shopping list updated successfully');
        }

        return redirect()->intended('/home')->with('warning', 'Unauthorized request');

    }
}
