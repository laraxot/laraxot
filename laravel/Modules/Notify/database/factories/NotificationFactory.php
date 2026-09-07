<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Notify\Models\Notification;
use Modules\User\Models\User;

use function Safe\json_encode;

/**
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\TestNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => (string) Str::uuid(),
            'data' => json_encode(['message' => $this->faker->sentence()]),
<<<<<<< HEAD
<<<<<<< HEAD
            'read_at' => null];
=======
            'read_at' => null,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'read_at' => null];
>>>>>>> a988596b (first)
    }
}
