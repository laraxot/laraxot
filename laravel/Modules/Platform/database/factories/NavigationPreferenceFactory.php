<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Platform\Models\NavigationPreference;

/**
 * @extends Factory<NavigationPreference>
 */
class NavigationPreferenceFactory extends Factory
{
    /** @var class-string<NavigationPreference> */
    protected $model = NavigationPreference::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
