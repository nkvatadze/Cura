<?php

namespace Database\Factories;

use App\Models\Complex;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Block>
 */
class BlockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'complex_id' => Complex::factory(),
            'name' => fake()->randomElement(['Block A', 'Block B', 'Block C', 'Block D', 'Block E']).' '.fake()->randomElement(['North', 'South', 'East', 'West', 'Central']),
            'flat_quantity' => fake()->numberBetween(10, 100),
            'commercial_space_quantity' => fake()->numberBetween(0, 20),
            'status' => fake()->boolean(80), // 80% chance of being active
            'construction_date' => fake()->dateTimeBetween('-3 years', '-1 year'),
            'completion_date' => fake()->optional(0.8)->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the block is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => true,
        ]);
    }

    /**
     * Indicate that the block is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => false,
        ]);
    }

    /**
     * Indicate that the block is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'completion_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    /**
     * Indicate that the block is under construction.
     */
    public function underConstruction(): static
    {
        return $this->state(fn (array $attributes) => [
            'completion_date' => null,
        ]);
    }

    /**
     * Indicate that the block has more flats than commercial spaces.
     */
    public function residential(): static
    {
        return $this->state(fn (array $attributes) => [
            'flat_quantity' => fake()->numberBetween(50, 100),
            'commercial_space_quantity' => fake()->numberBetween(0, 5),
        ]);
    }

    /**
     * Indicate that the block has more commercial spaces than flats.
     */
    public function commercial(): static
    {
        return $this->state(fn (array $attributes) => [
            'flat_quantity' => fake()->numberBetween(10, 30),
            'commercial_space_quantity' => fake()->numberBetween(15, 30),
        ]);
    }
}
