<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissionNames = [
            'read staff',
            'write staff',
            'read customer',
            'write customer', 
        ];

        foreach ($permissionNames as $name) {
            Permission::create(['name' => $name]);
        }
    }
}
