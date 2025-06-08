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
                'sub_name' => 'テストサブネーム01',
                'category_id' =>  1,
                'ulid' => Str::ulid(),
                'is_public' => 0,
                'is_pick_up' => 0,
            ],[
                'name' => 'テストネーム02',
                'sub_name' => 'テストサブネーム02',
                'category_id' =>  2,
                'ulid' => Str::ulid(),
                'is_public' => 0,
                'is_pick_up' => 1,
            ],[
                'name' => 'テストネーム03',
                'sub_name' => 'テストサブネーム03',
                'category_id' =>  3,
                'ulid' => Str::ulid(),
                'is_public' => 1,
                'is_pick_up' => 0,
            ],[
                'name' => 'テストネーム04',
                'sub_name' => 'テストサブネーム04',
                'category_id' =>  4,
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
