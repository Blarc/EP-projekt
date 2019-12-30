<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::query()->delete();
        //$role = Role::query()->create(['guard_name' => 'admin', 'name' => 'admin']);
        $role = Role::query()->create(['name' => 'admin']);
        $role->givePermissionTo('viewAdminHome');

        $role = Role::query()->create(['name' => 'customer']);
        //$role = Role::query()->create(['guard_name' => 'customer', 'name' => 'customer']);
        $role->givePermissionTo('viewCustomerHome');

        $role = Role::query()->create(['name' => 'seller']);
        //$role = Role::query()->create(['guard_name' => 'seller', 'name' => 'seller']);
        $role->givePermissionTo('viewSellerHome');

    }
}
