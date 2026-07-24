<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Reseller;

/**
 * @extends Factory<Reseller>
 */
class ResellerFactory extends Factory
{
    /** @var class-string<Reseller> */
    protected $model = Reseller::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => null,
            'name' => $this->faker->company().' Reseller',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
