<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pricelist>
 */
class PricelistFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'service' => fake()->word(),
            'description' => fake()->sentence(),
            'amount' => fake()->randomFloat(2, 15, 85), // Prijs tussen 15.00 en 85.00
            'category' => fake()->randomElement(['Knippen', 'Kleuren', 'Styling']),
        ];
    }
}
