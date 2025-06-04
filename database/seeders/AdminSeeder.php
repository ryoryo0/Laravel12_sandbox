<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => '管理者',
                'password' => Hash::make('Password123+'),
                'email' => 'admin@test.com',
                'role_id' => 1,
            ],[
                'name' => 'スタッフ',
                'password' => Hash::make('Password123+'),
                'email' => 'staff@test.com',
                'role_id' => 1,
            ],[
                'name' => '削除テスト用',
                'password' => Hash::make('Password123+'),
                'email' => 'deletestaff@test.com',
                'role_id' => 1,
            ]
            
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
