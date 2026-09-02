<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Comment\Models\Comment;
use Modules\User\Models\User;

use function Safe\json_encode;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Comment>
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'text' => $this->faker->paragraph(),
            'original_text' => $this->faker->paragraph(),
            'commentable_type' => $this->faker->randomElement(['Modules\Blog\Models\Post', 'Modules\Predict\Models\Predict']),
            'commentable_id' => $this->faker->numberBetween(1, 100),
            'commentator_type' => User::class,
            'commentator_id' => User::factory(),
            'parent_id' => null,
            'approved_at' => $this->faker->optional()->dateTime(),
            'extra' => $this->faker->optional()->randomElement([
                null,
                json_encode(['source' => 'web', 'ip' => $this->faker->ipv4()]),
            ]),
            'created_by' => $this->faker->optional()->uuid(),
            'updated_by' => $this->faker->optional()->uuid(),
        ];
    }

    /**
     * Indica che il commento è approvato.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [ // Unused; state callbacks ignore attributes
            'approved_at' => now(),
        ]);
    }

    /**
     * Indica che il commento è in attesa di approvazione.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [ // Unused; state callbacks ignore attributes
            'approved_at' => null,
        ]);
    }

    /**
     * Indica che il commento è una risposta a un altro commento.
     */
    public function asReply(Comment $parentComment): static
    {
        return $this->state(fn (array $attributes) => [ // Unused; state callbacks ignore attributes
            'parent_id' => $parentComment->id,
            'commentable_type' => $parentComment->commentable_type,
            'commentable_id' => $parentComment->commentable_id,
        ]);
    }
}
