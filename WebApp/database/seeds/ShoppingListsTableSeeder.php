<?php

use App\ShoppingList;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ShoppingListsTableSeeder extends Seeder
{
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        ShoppingList::query()->truncate();

        $faker = Factory::create();

        // And now, let's create some items in our database:
        for ($i = 0; $i < 5; $i++) {
            ShoppingList::query()->create([
                'name' => $faker->firstName,
                'user_id' => 3
            ]);
        }
    }
}
