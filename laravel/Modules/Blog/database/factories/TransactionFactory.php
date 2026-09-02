<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Blog\Models\Transaction;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Transaction>
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'model_type' => fake()->randomElement(['Modules\Blog\Models\Article', 'Modules\Blog\Models\Profile']),
            'model_id' => fake()->numberBetween(1, 100),
            'user_id' => fake()->uuid(),
            'credits' => fake()->numberBetween(1, 1000),
            'note' => fake()->text(),
            'date' => fake()->date(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
