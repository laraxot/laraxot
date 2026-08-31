<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Compliance\Models\RefrigerantCircuit;

/**
 * @extends Factory<RefrigerantCircuit>
 */
class RefrigerantCircuitFactory extends Factory
{
    /** @var class-string<RefrigerantCircuit> */
    protected $model = RefrigerantCircuit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
