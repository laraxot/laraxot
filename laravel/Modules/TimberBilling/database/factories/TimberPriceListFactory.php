<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Modules\TimberBilling\Models\TimberPriceList;

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
        $name = ucfirst(is_string($words) ? $words : implode(' ', array_map(static fn (mixed $word): string => (string) $word, $words)));

        return [
            'code' => Str::slug($name, '_'),
            'name' => $name,
            'is_active' => true,
        ];
    }
}
