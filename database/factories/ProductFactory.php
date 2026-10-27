<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->seller(),
            'category_id' => Category::factory(),
            'name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->randomFloat(2, 5, 200),
            'image' => 'https://picsum.photos/seed/' . fake()->uuid() . '/400/400',
            'location' => 'Shah Alam',
            'status' => Product::STATUS_ACTIVE,
        ];
    }

    public function sold(): static
    {
        return $this->state(fn () => ['status' => Product::STATUS_SOLD]);
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['status' => Product::STATUS_INACTIVE]);
    }
}
