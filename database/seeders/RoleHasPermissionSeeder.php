<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleHasPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'read staff',
            'write staff',
            'read customer',
            'write customer', 
        ];
        $role = Role::findByName('admin');
        $role->givePermissionTo($permissions);

        $permissions = [
            'read customer',
            'write customer', 
        ];
        $role = Role::findByName('staff');
        $role->givePermissionTo($permissions);
    }
}
