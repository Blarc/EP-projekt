<?php

use App\Admin;
use App\Customer;
use App\Seller;
use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        User::query()->truncate();
        //Admin::query()->truncate();
        //Customer::query()->truncate();
        //Seller::query()->truncate();
                
        $user = User::query()->create([
            'firstName' => "admin",
            'lastName' => "admin",
            'email' => 'admin@gmail.com',
            'password' => bcrypt('asdfasdf'),
        ]);
        $user->assignRole('admin');

        $user = User::query()->create([
            'firstName' => "customer",
            'lastName' => "customer",
            'email' => 'customer@gmail.com',
            //'address' => 'Večna pot 113',
            //'telephone_number' => '01 999 999',
            'password' => bcrypt('asdfasdf'),
        ]);
        $user->assignRole('customer');

        $user = User::query()->create([
            'firstName' => "seller",
            'lastName' => "seller",
            'email' => 'seller@gmail.com',
            'password' => bcrypt('asdfasdf'),
        ]);
        $user->assignRole('seller');
    }
}
