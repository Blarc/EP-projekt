<?php

namespace App\Http\Controllers;

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
}
