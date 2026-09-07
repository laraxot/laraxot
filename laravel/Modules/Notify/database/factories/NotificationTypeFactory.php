<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Notify\Models\NotificationType;

/**
 * @extends Factory<NotificationType>
 */
class NotificationTypeFactory extends Factory
{
    protected $model = NotificationType::class;
<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return array<string, mixed>
     */
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    /**
     * @return array<string, mixed>
     */
>>>>>>> a988596b (first)

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(90),
            'created_at' => $this->faker->dateTimeBetween('-1 year'),
<<<<<<< HEAD
<<<<<<< HEAD
            'updated_at' => $this->faker->dateTimeBetween('-1 year')];
=======
            'updated_at' => $this->faker->dateTimeBetween('-1 year'),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'updated_at' => $this->faker->dateTimeBetween('-1 year')];
>>>>>>> a988596b (first)
    }
}
