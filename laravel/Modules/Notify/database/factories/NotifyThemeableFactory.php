<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Notify\Models\NotifyTheme;
use Modules\Notify\Models\NotifyThemeable;

/**
 * NotifyThemeable Factory
 *
 * @extends Factory<NotifyThemeable>
 */
class NotifyThemeableFactory extends Factory
{
    protected $model = NotifyThemeable::class;
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
            'notify_theme_id' => NotifyTheme::factory(),
            'themeable_type' => $this->faker->randomElement([
                'Modules\\User\\Models\\User',
                'Modules\\User\\Models\\User', // Generic fallback instead of project-specific
                'Modules\\User\\Models\\User', // Generic fallback instead of project-specific
            ]),
<<<<<<< HEAD
<<<<<<< HEAD
            'themeable_id' => $this->faker->randomNumber()];
=======
            'themeable_id' => $this->faker->randomNumber(),
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'themeable_id' => $this->faker->randomNumber()];
>>>>>>> a988596b (first)
    }

    public function forUser(): static
    {
        return $this->state(fn (array $_attributes): array => [
<<<<<<< HEAD
<<<<<<< HEAD
            'themeable_type' => 'Modules\\User\\Models\\User']);
=======
            'themeable_type' => 'Modules\\User\\Models\\User',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'themeable_type' => 'Modules\\User\\Models\\User']);
>>>>>>> a988596b (first)
    }

    public function forPatient(): static
    {
        return $this->state(fn (array $_attributes): array => [
            'themeable_type' => 'Modules\\User\\Models\\User', // Generic fallback
        ]);
    }
}
