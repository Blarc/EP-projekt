<?php

use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        User::query()->truncate();
                
        $user = User::query()->create([
            'firstName' => "admin",
            'lastName' => "admin",
            'email' => 'admin@gmail.com',
            'telephone' => "",
            'password' => bcrypt('asdfasdf'),
            'role' => 'admin',
        ]);
        $user->assignRole('admin');
        $user->generateToken();

        $user = User::query()->create([
            'firstName' => "customer",
            'lastName' => "customer",
            'email' => 'customer@gmail.com',
            'telephone' => '01 999 999',
            'password' => bcrypt('asdfasdf'),
            'role' => 'customer',
        ]);
        $user->assignRole('customer');
        $user->generateToken();
        $address = App\Address::find(1);
        // $address->user()->associate($user);
        $user->address()->associate($address);
        $user->save();

        $user = User::query()->create([
            'firstName' => "seller",
            'lastName' => "seller",
            'email' => 'seller@gmail.com',
            'telephone' => "",
            'password' => bcrypt('asdfasdf'),
            'role' => 'seller',
        ]);
        $user->assignRole('seller');
        $user->generateToken();
    }
}
