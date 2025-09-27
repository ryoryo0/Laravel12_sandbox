<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productCategories = [
            [
                'name' => 'ピアス',
                'create_admin_id' => 1,
            ],[
                'name' => 'リング',
                'create_admin_id' => 1,
            ],[
                'name' => 'ネックレス',
                'create_admin_id' => 1,
            ],[
                'name' => 'チェーン',
                'create_admin_id' => 1,
            ],[
                'name' => 'キャンドル',
                'create_admin_id' => 1,
            ],
        ];

        foreach ($productCategories as $category) {
            Category::create($category);
        }
    }
}
