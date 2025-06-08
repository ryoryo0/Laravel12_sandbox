<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productCategories = [
            [
                'name' => 'pierce'
            ],[
                'name' => 'necklace'
            ],[
                'name' => 'fingerRing'
            ],[
                'name' => 'bees'
            ],
        ];

        foreach ($productCategories as $category) {
            ProductCategory::create($category);
        }
    }
}
