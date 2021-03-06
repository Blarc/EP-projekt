<?php

namespace App\Http\Controllers\Auth;

use App\Address;
use App\Http\Controllers\Controller;
use App\Http\Resources\UsersDetailsResource;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstName' => 'required|max:255',
            'lastName' => 'required|max:255',
            'street' => 'required|max:255',
            'post' => 'required|max:255',
            'postCode' => 'required|regex:/[0-9]{4}/',
            'telephone' => 'required|regex:/[0-9]{9}/',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'firstName' => $data['firstName'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
            'telephone' => $data['telephone'],
            'password' => bcrypt($data['password']),
            'role' => 'customer',
        ]);
        $user->assignRole('customer');
        $user->generateToken();

        $address = Address::create([
            'street' => $data['street'],
            'post' => $data['post'],
            'postCode' => $data['postCode'],
        ]);

        $address->save();
        $user->address()->associate($address);
        $user->save();

        return $user;
    }

    protected function registered(Request $request, $user)
    {
        $user->generateToken();

        return new UsersDetailsResource($user);
    }
}
