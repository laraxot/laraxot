<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Notify\Models\NotificationChannel;

use function Safe\json_encode;

/**
 * @extends Factory<NotificationChannel>
 */
class NotificationChannelFactory extends Factory
{
    /** @var class-string<NotificationChannel> */
    protected $model = NotificationChannel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'driver' => 'email',
            'config' => json_encode(['smtp_host' => 'localhost']),
            'is_enabled' => true,
<<<<<<< HEAD
<<<<<<< HEAD
            'priority' => 1];
=======
            'priority' => 1,
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'priority' => 1];
>>>>>>> a988596b (first)
    }

    /**
     * Indicate that the channel is enabled.
     */
    public function enabled(): static
    {
        return $this->state(fn (array $attributes) => [
<<<<<<< HEAD
<<<<<<< HEAD
            'is_enabled' => true]);
=======
            'is_enabled' => true,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'is_enabled' => true]);
>>>>>>> a988596b (first)
    }

    /**
     * Indicate that the channel is disabled.
     */
    public function disabled(): static
    {
        return $this->state(fn (array $attributes) => [
<<<<<<< HEAD
<<<<<<< HEAD
            'is_enabled' => false]);
=======
            'is_enabled' => false,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'is_enabled' => false]);
>>>>>>> a988596b (first)
    }

    /**
     * Configure the factory for email channels.
     */
    public function email(): static
    {
        return $this->state(fn (array $attributes) => [
<<<<<<< HEAD
<<<<<<< HEAD
            'driver' => 'email']);
=======
            'driver' => 'email',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'driver' => 'email']);
>>>>>>> a988596b (first)
    }

    /**
     * Configure the factory for SMS channels.
     */
    public function sms(): static
    {
        return $this->state(fn (array $attributes) => [
<<<<<<< HEAD
<<<<<<< HEAD
            'driver' => 'sms']);
=======
            'driver' => 'sms',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'driver' => 'sms']);
>>>>>>> a988596b (first)
    }
}
