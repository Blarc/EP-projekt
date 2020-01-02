<?php

namespace App\Http\Controllers;

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

    public function getPreferences() {
        
        $user = auth()->user();
        $address = (new AddressesController)->get($user->address_id);
        
        if ($user->hasPermissionTo('viewAdminHome')) {
            return view('admin.preferences');
        }

        if ($user->hasPermissionTo('viewCustomerHome')) {
            return view('customer.preferences')->with('address', $address);
        }

        if ($user->hasPermissionTo('viewSellerHome')) {
            return view('seller.preferences');
        }
    }

    public function postPreferences(Request $request) {
        
        $user = auth()->user();

        $response = (new UsersController)->put($request, $user->id);
    
        if (!isset($response->id)) {
            return redirect()->back()->with('error', $response->original);
        }
        return redirect()->back()->with('success', 'Profile updated successfully');
        
    }
}
