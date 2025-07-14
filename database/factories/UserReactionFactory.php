<?php

namespace Database\Factories;

use App\Enums\ReactionType;
use App\Models\Block;
use App\Models\Complex;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserReaction>
 */
class UserReactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Randomly choose between Complex and Block as reactable
        $reactableType = fake()->randomElement([Complex::class, Block::class]);
        $reactable = $reactableType::factory()->create();

        return [
            'user_id' => User::factory(),
            'reactable_id' => $reactable->id,
            'reactable_type' => $reactableType,
            'reaction' => ReactionType::LIKE->value,
        ];
    }
}
