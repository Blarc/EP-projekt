<?php

use App\ShoppingList;
use Illuminate\Database\Seeder;

class ShoppingListsTableSeeder extends Seeder
{
    public function run()
    {
        // Let's truncate our existing records to start from scratch.
        ShoppingList::query()->truncate();
    }
}
