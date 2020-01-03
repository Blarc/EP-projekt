<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        Permission::query()->delete();
        //$permission = Permission::query()->create(['guard_name' => 'admin', 'name' => 'viewAdminHome']);
        //$permission = Permission::query()->create(['guard_name' => 'customer', 'name' => 'viewCustomerHome']);
        //$permission = Permission::query()->create(['guard_name' => 'seller', 'name' => 'viewSellerHome']);
        $role = Permission::query()->create(['name' => 'viewAdminHome']);
        $role = Permission::query()->create(['name' => 'viewCustomerHome']);
        $role = Permission::query()->create(['name' => 'viewSellerHome']);

        $role = Permission::query()->create(['name' => 'viewAdminEditProfile']);
        $role = Permission::query()->create(['name' => 'viewCustomerEditProfile']);
        $role = Permission::query()->create(['name' => 'viewSellerEditProfile']);

    }
}
