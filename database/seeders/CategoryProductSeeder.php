<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CategoryProduct;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::select('id')->get();
        foreach ($categories as $category) {
            CategoryProduct::factory()->create([
                'category_id' => $category->id,
                'product_id' => Product::inRandomOrder()->first()->id,
            ]);
        }
    }
}
