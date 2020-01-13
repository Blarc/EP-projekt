<?php

use App\Address;
use Illuminate\Database\Seeder;
use Faker\Factory;

class AddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Address::query()->truncate();

        $faker = Factory::create();

        // And now, let's create some items in our database:
        for ($i = 0; $i < 50; $i++) {
            Address::query()->create([
                'street' => $faker->streetAddress,
                'post' => $faker->city,
                'postCode' => $faker->postcode
            ]);
        }
    }
}
