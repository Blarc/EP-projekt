<?php

use App\Address;
use Illuminate\Database\Seeder;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Address::query()->truncate();

        $address = Address::query()->create([
            'street' => "Večna Pot 113",
            'post' => 'Ljubljana',
            'postCode' => '1000',
        ]);
    }
}
