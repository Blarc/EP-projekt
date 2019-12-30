<?php

use App\Item;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{

    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        Item::query()->truncate();

        $faker = Factory::create();

        // And now, let's create some items in our database:
        for ($i = 0; $i < 50; $i++) {
            Item::query()->create([
                'name' => $faker->city,
                'description' => $faker->paragraph
            ]);
        }

    }
}
