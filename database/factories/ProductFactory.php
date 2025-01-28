<?php

namespace Database\Factories;

use App\Models\ProductsCategory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = ProductsCategory::inRandomOrder()->first();
        $status = ['new', 'active', 'inactive'];
        return [
            'supplier_id' => $status == 'new' ? User::inRandomOrder()->first()->id : null,
            'name_ar' => fake()->unique()->name(),
            'name_en' => fake('en')->unique()->name(),
            'category_id' => $category->id,
            'sub_category_id' => $category->subCategories()->inRandomOrder()->first()->id,
            'description' => fake()->paragraph(),
            'status' => $status[rand(0,2)],
        //     'max_order_qty' => fake()->numberBetween(1, 100),
        //     'qty' => fake()->numberBetween(10, 30),
        //     'payment_type' => fake()->name(),
        //     'delivery_method' => fake()->name(),
        //     'price' => fake()->numberBetween(100, 2000),
        ];
    }
}
