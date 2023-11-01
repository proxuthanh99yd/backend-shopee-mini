<?php

namespace Database\Factories;

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
        return [
            'name' => fake()->unique()->name,
            'description' => fake()->paragraphs(5, true),
            'image' => 'fakeProduct.jpg',
            'active' => 1,
            'discount' => random_int(0, 20),
            'category_id' => random_int(1, 5),
            'brand_id' => random_int(1, 9),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
