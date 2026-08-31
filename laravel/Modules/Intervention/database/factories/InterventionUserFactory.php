<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Intervention\Models\InterventionUser;

/**
 * @extends Factory<InterventionUser>
 */
class InterventionUserFactory extends Factory
{
    /** @var class-string<InterventionUser> */
    protected $model = InterventionUser::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
