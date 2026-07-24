<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Customer;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /** @var class-string<Customer> */
    protected $model = Customer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'legal_name' => $this->faker->company(),
            'vat_number' => $this->faker->optional()->numerify('IT###########'),
            'tax_code' => $this->faker->optional()->bothify('??????##?##?###?'),
            'email' => $this->faker->optional()->companyEmail(),
            'phone' => $this->faker->optional()->phoneNumber(),
            'address_line1' => $this->faker->optional()->streetAddress(),
            'city' => $this->faker->optional()->city(),
            'postal_code' => $this->faker->optional()->postcode(),
            'province' => $this->faker->optional()->randomElement(['MI', 'RM', 'TO', 'NA', 'FI']),
            'country_code' => 'IT',
            'payment_method' => 'bonifico',
            'is_active' => true,
        ];
    }
}
