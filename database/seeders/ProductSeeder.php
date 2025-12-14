<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'テストネーム01',
                'description' => 'ディスクリプション01',
                'create_admin_id' => 1,
                'ulid' => Str::ulid(),
                'code' => '2025070101HR',
                'price' => 1000,
                'is_public' => 0,
                'is_pick_up' => 0,
            ],[
                'name' => 'テストネーム02',
                'description' => 'ディスクリプション02',
                'create_admin_id' => 1,
                'ulid' => Str::ulid(),
                'code' => '2025070102HR',
                'price' => 2000,
                'is_public' => 0,
                'is_pick_up' => 1,
            ],[
                'name' => 'テストネーム03',
                'description' => 'ディスクリプション03',
                'create_admin_id' => 1,
                'ulid' => Str::ulid(),
                'code' => '2025070103HR',
                'price' => 3000,
                'is_public' => 1,
                'is_pick_up' => 0,
            ],[
                'name' => 'テストネーム04',
                'description' => 'ディスクリプション04',
                'create_admin_id' => 1,
                'ulid' => Str::ulid(),
                'code' => '2025070104HR',
                'price' => 4000,
                'is_public' => 1,
                'is_pick_up' => 1,
            ]
        ];

        foreach ($products as $product) {
                Product::create($product);
        }
    }
}
