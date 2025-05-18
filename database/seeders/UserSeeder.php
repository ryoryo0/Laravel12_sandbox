<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

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
                'password' => Crypt::encryptString('Password123+'),
                'email' => 'admin@test.com',
            ],[
                'name' => 'スタッフ',
                'password' => Crypt::encryptString('Password123+'),
                'email' => 'staff@test.com',

            ],[
                'name' => '一般訪問者',
                'password' => Crypt::encryptString('Password123+'),
                'email' => '@test.com',
            ]
        ];
       
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
