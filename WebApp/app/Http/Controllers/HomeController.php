<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AddressesController;
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
        
        if ($user->hasPermissionTo('viewAdminHome')) {
            return view('admin.home');
        }

        if ($user->hasPermissionTo('viewCustomerHome')) {
            return view('customer.home');
        }

        if ($user->hasPermissionTo('viewSellerHome')) {
            return view('seller.home');
        }

        return view('welcome');
    }

    // preferences of current user
    public function getPreferences() {
        
        $user = auth()->user();
        $address = (new AddressesController)->get($user->address_id);
        
        if ($user->hasPermissionTo('viewAdminPreferences')) {
            return view('admin.preferences');
        }

        if ($user->hasPermissionTo('viewCustomerPreferences')) {
            return view('customer.preferences')->with('address', $address);
        }

        if ($user->hasPermissionTo('viewSellerPreferences')) {
            return view('seller.preferences');
        }
    }

    public function postPreferences(Request $request) {
        
        $user = auth()->user();

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
            return view('admin.seller-preferences')->with('seller', $seller);
        }

        if ($user->hasRole('seller')) {
            $customer = $user->customers->find($id);
            $address = $customer->address->find($customer->address_id);
            $data = [
                'address'  => $address,
                'customer' => $customer,
            ];
            return view('seller.customer-preferences')->with($data);
        }

    }

    public function editManagedProfile(Request $request, $id) {

        $user = auth()->user();
        if ($user->hasRole('admin')) {
            $seller = $user->sellers->find($id);
            $response = (new UsersController)->putAdminOrSeller($request, $seller->id);
        }

        else {
            $customer = $user->customers->find($id);
            $response = (new UsersController)->put($request, $customer->id);
        }
    
        //dd($response);
        if (!isset($response->id)) {
            return redirect()->back()->with('error', $response->original);
        }
        return redirect()->back()->with('success', 'Profile updated successfully');

    }

    public function createManagedProfile(Request $request) {
        // TODO
        return view('admin.create-seller');
    }

}
