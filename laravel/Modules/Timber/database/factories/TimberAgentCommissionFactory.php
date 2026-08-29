<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberAgent;
use Modules\Timber\Models\TimberAgentCommission;

/**
 * @extends Factory<TimberAgentCommission>
 */
final class TimberAgentCommissionFactory extends Factory
{
    protected $model = TimberAgentCommission::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'agent_id' => TimberAgent::factory(),
            'scope_type' => 'default',
            'category' => null,
            'product_id' => null,
            'rate_percent' => $this->faker->randomFloat(2, 1, 8),
        ];
    }
}
