<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\ProductsCategory;
use App\Models\ProductsSubCategories;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProductsSubCategories::query()->delete();
        ProductsCategory::query()->delete();
        ProductsCategory::factory(7)->create();
        $categories = ProductsCategory::all();
        foreach($categories as $category){
            for ($i=0; $i < rand(1,3); $i++) {
                ProductsSubCategories::query()->create([
                    'category_id' => $category->id,
                    'admin_id' => Admin::first()->id,
                    'name_ar' => fake()->userName(),
                    'name_en' => fake()->userName(),
                ]);
            }
        }
    }
}
