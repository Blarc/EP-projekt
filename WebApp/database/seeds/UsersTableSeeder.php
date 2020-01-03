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
                
        $admin = User::query()->create([
            'firstName' => "admin",
            'lastName' => "admin",
            'email' => 'admin@gmail.com',
            'telephone' => "",
            'password' => bcrypt('asdfasdf'),
            'role' => 'admin',
        ]);
        
        $admin->assignRole('admin');
        $admin->generateToken();

        $customer = User::query()->create([
            'firstName' => "customer",
            'lastName' => "customer",
            'email' => 'customer@gmail.com',
            'telephone' => '01 999 999',
            'password' => bcrypt('asdfasdf'),
            'role' => 'customer',
        ]);
        $customer->assignRole('customer');
        $customer->generateToken();
        $address = App\Address::find(1);
        $customer->address()->associate($address);
        $customer->save();

        $seller = User::query()->create([
            'firstName' => "seller",
            'lastName' => "seller",
            'email' => 'seller@gmail.com',
            'telephone' => "",
            'password' => bcrypt('asdfasdf'),
            'role' => 'seller',
        ]);
        $seller->assignRole('seller');
        $seller->generateToken();

        $admin->sellers()->sync($seller);
        $seller->customers()->sync($customer);
    }
}
