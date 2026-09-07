<?php

declare(strict_types=1);

namespace Modules\User\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Models\Notification;
<<<<<<< HEAD
<<<<<<< HEAD

/**
=======
=======
>>>>>>> 87273113 (.)
use Modules\User\Models\User;

/**
 * Notification Factory
 *
 * Factory for creating Notification model instances for testing and seeding.
 *
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======

/**
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
 * @extends Factory<Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
<<<<<<< HEAD
<<<<<<< HEAD
=======
     *
     * @var class-string<Notification>
>>>>>>> f548be94 (.)
=======
     *
     * @var class-string<Notification>
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     */
    protected $model = Notification::class;

    /**
     * Define the model's default state.
<<<<<<< HEAD
<<<<<<< HEAD
     */
    /**
=======
     *
>>>>>>> f548be94 (.)
=======
     *
=======
     */
    /**
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
     * @return array<string, mixed>
     */
    public function definition(): array
    {
<<<<<<< HEAD
<<<<<<< HEAD
        return [];
=======
=======
>>>>>>> 87273113 (.)
        return [
            'id' => $this->faker->uuid(),
            'type' => $this->faker->randomElement([
                'App\\Notifications\\UserRegistered',
                'App\\Notifications\\PasswordReset',
                'App\\Notifications\\AppointmentReminder',
                'App\\Notifications\\SystemUpdate',
            ]),
            'notifiable_type' => User::class,
            'notifiable_id' => User::factory(),
            'data' => [
                'title' => $this->faker->sentence(4),
                'message' => $this->faker->text(200),
                'action_url' => $this->faker->optional()->url(),
                'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            ],
            'read_at' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * Create an unread notification.
     *
     * @return static
     */
    public function unread(): static
    {
        return $this->state(fn(array $_attributes): array => [
            'read_at' => null,
        ]);
    }

    /**
     * Create a read notification.
     *
     * @return static
     */
    public function read(): static
    {
        return $this->state(fn(array $_attributes): array => [
            'read_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Create notification for a specific user.
     *
     * @param User $user
     * @return static
     */
    public function forUser(User $user): static
    {
        return $this->state(fn(array $_attributes): array => [
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
        ]);
    }

    /**
     * Create notification with high priority.
     *
     * @return static
     */
    public function highPriority(): static
    {
        return $this->state(fn(array $attributes): array => [
            'data' => array_merge(
                is_array($attributes['data'] ?? null)
                    ? $attributes['data']
                    : [
                        'title' => $this->faker->sentence(4),
                        'message' => $this->faker->text(200),
                        'action_url' => $this->faker->optional()->url(),
                        'priority' => 'medium',
                    ],
                [
                    'priority' => 'high',
                ],
            ),
        ]);
    }

    /**
     * Create notification with specific type.
     *
     * @param string $type
     * @return static
     */
    public function ofType(string $type): static
    {
        return $this->state(fn(array $_attributes): array => [
            'type' => $type,
        ]);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        return [];
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
