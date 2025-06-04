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
        $permissions = [
            [
                'name' => 'read staff',
                'guard_name' => 'admin',
            ],[
                'name' => 'write staff',
                'guard_name' => 'admin',
            ],[
                'name' => 'read customer',
                'guard_name' => 'admin',
            ],[
                'name' => 'write customer',
                'guard_name' => 'admin',
            ]
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
            ]);
        }
    }
}
