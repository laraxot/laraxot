<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Comment\Models\CommentNotificationOptOut;

/**
 * @extends Factory<CommentNotificationOptOut>
 */
class CommentNotificationOptOutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = CommentNotificationOptOut::class;

    /**
     * Define the model's default state.
     */
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [];
    }
}
