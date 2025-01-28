<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductsDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::query()->delete();
        ProductsDetail::query()->delete();
        Product::factory(1000)->create();

        DB::statement('UPDATE `products` SET `supplier_id` = ( SELECT `id`  FROM `users`  ORDER BY RAND() LIMIT 1);');

        $types = [
            'color', 'text'
        ];
        $colors = ['#000' , '#ff0', '#f00', '#ccc', '#fff'];
        $products = Product::all();
        foreach($products as $product){
            for ($i=0; $i < rand(1,3); $i++) {
                $type = $types[rand(0,1)];
                ProductsDetail::query()->create([
                    'product_id' => $product->id,
                    'type' => $type,
                    'name' => $type,
                    'key' => $type == 'color' ? $colors[rand(0,4)] : fake()->userName(),
                ]);
            }
        }
    }
}
