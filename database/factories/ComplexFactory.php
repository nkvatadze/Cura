<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Complex>
 */
class ComplexFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company().' '.fake()->randomElement(['Complex', 'Residences', 'Apartments', 'Towers', 'Plaza']),
            'location' => fake()->city().', '.fake()->state(),
            'block_quantity' => fake()->numberBetween(1, 20),
            'status' => fake()->boolean(80), // 80% chance of being active
            'construction_date' => fake()->dateTimeBetween('-5 years', '-1 year'),
            'completion_date' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the complex is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the complex is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Indicate that the complex is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completion_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the complex is under construction.
     */
    public function underConstruction(): static
    {
        return $this->state(fn (array $attributes) => [
            'completion_date' => null,
        ]);
    }
}
