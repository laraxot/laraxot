<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Notify\Models\NotificationLog;
use Modules\User\Models\User;

use function Safe\json_encode;

/**
 * @extends Factory<NotificationLog>
 */
class NotificationLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = NotificationLog::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'notifiable_type' => User::class,
            'notifiable_id' => 1,
            'title' => $this->faker->sentence(),
            'content' => $this->faker->paragraph(),
            'channels' => json_encode(['email']),
            'data' => json_encode(['message' => $this->faker->sentence()]),
            'sent_at' => now(),
            'status' => NotificationLog::STATUS_SENT,
<<<<<<< HEAD
<<<<<<< HEAD
            'error' => null];
=======
            'error' => null,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'error' => null];
>>>>>>> a988596b (first)
    }
}
