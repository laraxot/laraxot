<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberCustomer;

/**
 * @extends Factory<TimberCustomer>
 */
final class TimberCustomerFactory extends Factory
{
    protected $model = TimberCustomer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('CL-####')),
            'business_name' => $this->faker->company(),
            'vat_number' => 'IT'.$this->faker->unique()->numerify('###########'),
            'tax_code' => $this->faker->numerify('##################'),
            'is_customer' => true,
            'is_supplier' => false,
            'address_line1' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'city' => $this->faker->city(),
            'province' => $this->faker->randomElement(['MI', 'RM', 'TO', 'NA', 'FI', 'BO', 'VE', 'GE']),
            'country' => 'Italia',
            'contact_person' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'payment_terms' => 'Bonifico 30 gg',
            'send_documents_by_email' => false,
            'invoice_with_withholding' => false,
            'is_active' => true,
        ];
    }

    public function supplier(): self
    {
        return $this->state(fn (): array => ['is_supplier' => true]);
    }
}
