<?php

use App\User;
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
            'email' => 'jakob@ep.si',
            'telephone' => "",
            'password' => bcrypt('asdfasdf'),
            'role' => 'admin',
            'active' => true,
        ]);

        $admin->assignRole('admin');
        $admin->generateToken();

        $customer = User::query()->create([
            'firstName' => "customer",
            'lastName' => "customer",
            'email' => 'jan@ep.si',
            'telephone' => '01 999 999',
            'password' => bcrypt('asdfasdf'),
            'role' => 'customer',
            'active' => true,
        ]);
        $customer->assignRole('customer');
        $customer->generateToken();
        $address = App\Address::find(1);
        $customer->address()->associate($address);
        $customer->save();

        $seller = User::query()->create([
            'firstName' => "seller",
            'lastName' => "seller",
            'email' => 'franc@ep.si',
            'telephone' => "",
            'password' => bcrypt('asdfasdf'),
            'role' => 'seller',
            'active' => true,
        ]);
        $seller->assignRole('seller');
        $seller->generateToken();

        $admin->sellers()->sync($seller);
        $seller->customers()->sync($customer);
    }
}
