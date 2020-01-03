<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        Role::query()->delete();
        
        $role = Role::query()->create(['name' => 'admin']);
        $role->givePermissionTo('viewAdminHome');
        $role->givePermissionTo('viewAdminPreferences');

        $role = Role::query()->create(['name' => 'customer']);
        $role->givePermissionTo('viewCustomerHome');
        $role->givePermissionTo('viewCustomerPreferences');

        $role = Role::query()->create(['name' => 'seller']);
        $role->givePermissionTo('viewSellerHome');
        $role->givePermissionTo('viewSellerPreferences');

    }
}
