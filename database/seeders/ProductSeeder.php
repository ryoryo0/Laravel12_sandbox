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
                'category_id' =>  1,
                'create_admin_id' => 1,
                'ulid' => Str::ulid(),
                'is_public' => 0,
                'is_pick_up' => 0,
            ],[
                'name' => 'テストネーム02',
                'description' => 'ディスクリプション02',
                'category_id' =>  2,
                'create_admin_id' => 1,
                'ulid' => Str::ulid(),
                'is_public' => 0,
                'is_pick_up' => 1,
            ],[
                'name' => 'テストネーム03',
                'description' => 'ディスクリプション03',
                'category_id' =>  3,
                'create_admin_id' => 1,
                'ulid' => Str::ulid(),
                'is_public' => 1,
                'is_pick_up' => 0,
            ],[
                'name' => 'テストネーム04',
                'description' => 'ディスクリプション04',
                'category_id' =>  4,
                'create_admin_id' => 1,
                'ulid' => Str::ulid(),
                'is_public' => 1,
                'is_pick_up' => 1,
            ]
        ];

        foreach ($products as $product) {
                Product::create($product);
        }
    }
}
