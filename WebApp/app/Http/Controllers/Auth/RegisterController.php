<?php

namespace App\Http\Controllers\Auth;

use App\Address;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
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
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

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

        $u = User::where('email' , $data['email'])->first();
        if ($u !== null) {
            return redirect()->back()
                ->with('error', 'Email already exists!')
                ->withInput($request->input());
        }

        $user = User::create([
            'firstName' => filter_var($data['firstName'], FILTER_SANITIZE_SPECIAL_CHARS),
            'lastName' => filter_var($data['firstName'], FILTER_SANITIZE_SPECIAL_CHARS),
            'email' => filter_var($data['firstName'], FILTER_SANITIZE_SPECIAL_CHARS),
            'telephone' => filter_var($data['firstName'], FILTER_SANITIZE_SPECIAL_CHARS),
            'password' => filter_var(bcrypt($data['firstName']), FILTER_SANITIZE_SPECIAL_CHARS),
            'role' => 'customer',
            'active' => true,
        ]);
        $user->assignRole('customer');
        $user->generateToken();

        $address = Address::create([
            'street' => filter_var($data['street'], FILTER_SANITIZE_SPECIAL_CHARS),
            'post' => filter_var($data['post'], FILTER_SANITIZE_SPECIAL_CHARS),
            'postCode' => filter_var($data['postCode'], FILTER_SANITIZE_SPECIAL_CHARS),
        ]);

        $address->save();
        $user->address()->associate($address);
        $user->save();

        return $user;
    }
}
