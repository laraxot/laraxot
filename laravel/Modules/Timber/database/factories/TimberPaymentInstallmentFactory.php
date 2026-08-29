<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberPaymentInstallment;

/**
 * @extends Factory<TimberPaymentInstallment>
 */
final class TimberPaymentInstallmentFactory extends Factory
{
    protected $model = TimberPaymentInstallment::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'direction' => 'in',
            'due_date' => now()->addDays(30)->toDateString(),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'payment_method_code' => 'bonifico',
            'status_code' => 'da_saldare',
        ];
    }

    public function settled(): self
    {
        return $this->state(fn (): array => ['status_code' => 'saldato', 'paid_at' => now()]);
    }
}
