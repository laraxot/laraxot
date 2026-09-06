<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\Timber\Models\TimberPriceList;

/**
 * @extends Factory<TimberPriceList>
 */
final class TimberPriceListFactory extends Factory
{
    protected $model = TimberPriceList::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $words = $this->faker->unique()->words(2, true);
        $wordsStr = is_string($words) ? $words : implode(' ', array_map(static fn (mixed $word): string => is_string($word) ? $word : '', $words));
        $name = ucfirst($wordsStr);

        return [
            'code' => Str::slug($name, '_'),
            'name' => $name,
            'is_active' => true,
        ];
    }
}
