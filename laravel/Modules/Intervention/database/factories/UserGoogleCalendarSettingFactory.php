<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Intervention\Models\UserGoogleCalendarSetting;

/**
 * @extends Factory<UserGoogleCalendarSetting>
 */
class UserGoogleCalendarSettingFactory extends Factory
{
    /** @var class-string<UserGoogleCalendarSetting> */
    protected $model = UserGoogleCalendarSetting::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->unique()->numberBetween(1, 999999),
            'enabled' => true,
            'google_email' => $this->faker->optional()->safeEmail(),
            'calendar_id' => 'primary',
        ];
    }
}
