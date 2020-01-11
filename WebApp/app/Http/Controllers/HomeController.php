<?php

namespace App\Http\Controllers;

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
        $response = ($user->role == 'admin' || $user->role == 'seller' ?
            (new UsersController)->putAdminOrSeller($request, $user->id) : (new UsersController)->put($request, $user->id));

        if (!isset($response->id)) {
            return redirect()->back()->with('error', $response->original);
        }
        return redirect()->back()->with('success', 'Profile updated successfully');

    }

    public function viewManagedProfile(Request $request, $id) {
        
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $seller = $user->sellers->find($id);
            return view('admin.seller-edit-profile')->with('seller', $seller);
        }

        if ($user->hasRole('seller')) {
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

        if ($user->hasRole('admin')) {
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

        if ($user->hasRole('admin')) {
            return view('admin.create-form');
        }

        else if ($user->hasRole('seller')) {
            return view('seller.create-form');
        }

    }

    public function createManagedProfile(Request $request) {

        $user = auth()->user();

        if ($user->hasRole('admin')) {

            $u = User::where('email' , $request->input('email'))->first();
            if ($u !== null) {
                return redirect()->back()
                    ->with('error', 'Email already exists!')
                    ->withInput($request->input());
            }

            $seller = User::create([
                'firstName' => strip_tags($request->input('firstName')),
                'lastName' => strip_tags($request->input('lastName')),
                'email' => strip_tags($request->input('email')),
                'telephone' => "",
                'password' => strip_tags(bcrypt($request['password'])),
                'role' => 'seller',
                'active' => true,
            ]);

            $seller->assignRole('seller');
            $seller->generateToken();
            $seller->save();
            $user->sellers()->attach($seller);

            return redirect()->intended('/home')->with('success', 'Seller created successfully');

        }

        else if ($user->hasRole('seller')) {

            $u = User::where('email' , $request->input('email'))->first();
            if ($u !== null) {
                return redirect()->back()
                    ->with('error', 'Email already exists!')
                    ->withInput($request->input());
            }

            $customer = User::create([
                'firstName' => strip_tags($request->input('firstName')),
                'lastName' => strip_tags($request->input('lastName')),
                'email' => strip_tags($request->input('email')),
                'telephone' => strip_tags($request->input('telephone')),
                'password' => strip_tags(bcrypt($request['password'])),
                'role' => 'customer',
                'active' => true,
            ]);
            $customer->assignRole('customer');
            $customer->generateToken();

            $address = Address::create([
                'street' => strip_tags($request->input('street')),
                'post' => strip_tags($request->input('post')),
                'postCode' => strip_tags($request->input('postCode'))
            ]);

            $customer->address()->associate($address);
            $customer->save();
            $user->customers()->attach($customer);

            return redirect()->intended('/home')->with('success', 'Customer created successfully');
        }

        return view('seller.home')->with('error', 'Error occured when creating new profile!');

    }

    public function changeProfileStatus($id) {

        $user = auth()->user();

        $profile = ($user->hasRole('admin') ? $user->sellers->find($id) : $user->customers->find($id));
        $profile->active = !$profile->active;
        $profile->save();

        return redirect()->intended('/home');

    }

    public function editItemSeller(Request $request, $id){

        $item = Item::find($id);
        $response = (new ItemsController)->put($request, $item->id);

        //dd($response);
        if (!isset($response->id)) {
            return redirect()->back()->with('error', $response->original);
        }
        return redirect()->intended('/item-manage')->with('success', 'Item updated successfully');
    }

    public function createItem(Request $request){
        $item = Item::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'price' => $request['price']
        ]);
        $item->save();
        return redirect()->intended('/item-manage')->with('success', 'Item created successfully');
    }

    public function createShoppingList(Request $request, $id){
        $user = auth()->user();
        $sl = ShoppingList::create([
            'name' => $request['name'],
            'status' => '3',
            'user_id' => $user->id
        ]);
        $sl->save();
        return redirect()->back()->with('success', 'Shopping list created successfully');
    }

    public function viewCreateItemForm(){
        // TODO
        return view('seller.create-item');
    }

    public function shoppingListsShow(){
        $user = auth()->user();
        $sls = $user->shoppingLists;
        return view('customer.shopping-lists')->with('shoppingLists', $sls);
    }

    public function accept($id)
    {
        DB::table('shopping_lists')->where('id', $id)->update(['status' => '1']);
        return redirect('/seller/shoppingLists');
    }

    public function setAmountShoppingList(Request $request, $slid, $iid){
        $shoppingList = ShoppingList::find($slid);
//        $items_amount = $shoppingList->items()->where('item_id', $iid)->first()->pivot->items_amount;
        $shoppingList->items()->updateExistingPivot($iid, array('items_amount' => $request['items_amount']));
        $shoppingList->save();
//        return redirect('/seller/shoppingLists');
        return redirect()->back()->with('success', 'Shopping list updated successfully');

    }



    public function deleteItemShoppingList($slid, $iid){
        $shoppingList = ShoppingList::find($slid);
        $shoppingList->items()->detach($iid);
        $shoppingList->save();
        return redirect()->back()->with('success', 'Shopping list updated successfully');

    }
}
