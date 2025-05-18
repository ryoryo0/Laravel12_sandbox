<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $users = [
            [
                'name' => '管理者',
                'password' => Hash::make('Password123+'),
                'email' => 'admin@test.com',
                'role' => 'admin',
            ],[
                'name' => 'スタッフ',
                'password' => Hash::make('Password123+'),
                'email' => 'staff@test.com',
                'role' => 'staff',
            ],[
                'name' => '一般訪問者',
                'password' => Hash::make('Password123+'),
                'email' => 'customer@test.com',
                'role' => 'customer',
            ]
        ];
       
        $user = new User();
        $fillable = array_flip($user->getFillable());
        foreach ($users as $user) {
            $createUserData = array_intersect_key($user, $fillable);
            User::create($createUserData)->assignRole($user['role']);
        }
    }
}
