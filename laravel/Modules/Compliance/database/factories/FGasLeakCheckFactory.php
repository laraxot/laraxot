<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\FGasLeakCheck;

/**
 * @extends Factory<FGasLeakCheck>
 */
class FGasLeakCheckFactory extends Factory
{
    /** @var class-string<FGasLeakCheck> */
    protected $model = FGasLeakCheck::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
