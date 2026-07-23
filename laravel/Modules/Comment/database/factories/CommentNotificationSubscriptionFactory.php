<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Comment\Models\CommentNotificationSubscription;

/**
 * @extends Factory<CommentNotificationSubscription>
 */
class CommentNotificationSubscriptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = CommentNotificationSubscription::class;

    /**
     * Define the model's default state.
     */
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [];
    }
}
