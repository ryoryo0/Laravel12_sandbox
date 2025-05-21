<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $customers = [
            [
                'name' => '一般訪問者',
                'password' => Hash::make('Password123+'),
                'email' => 'customer@test.com',
                'role' => 'customer',
            ]
        ];
       
        $customer = new Customer();
        $fillable = array_flip($customer->getFillable());
        foreach ($customers as $customer) {
            $createUserData = array_intersect_key($customer, $fillable);
            Customer::create($createUserData)->assignRole($customer['role']);
        }
    }
}
