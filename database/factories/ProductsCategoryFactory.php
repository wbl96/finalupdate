<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductsCategory>
 */
class ProductsCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name_ar' => fake()->unique()->randomElement([
                'الاجبان',
                'المخبوزات',
                'اكسسوارات',
                'المحمصة',
                'اللحوم',
                'الخضار والفواكه',
                'الحلويات'
            ]),
            'name_en' => fake('en')->unique()->randomElement([
                'Cheese',
                'Baked goods',
                'Accessories',
                'Roaster',
                'Meat',
                'Fruits and vegetables',
                'Sweets'
            ]),
            'admin_id' => Admin::inRandomOrder()->first()->id,
        ];
    }
}
