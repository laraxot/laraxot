<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Platform\Models\PlatformSetting;

/**
 * @extends Factory<PlatformSetting>
 */
class PlatformSettingFactory extends Factory
{
    /** @var class-string<PlatformSetting> */
    protected $model = PlatformSetting::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->slug(2),
            'value_json' => ['value' => $this->faker->boolean()],
            'updated_by_user_id' => (string) Str::uuid(),
        ];
    }
}
