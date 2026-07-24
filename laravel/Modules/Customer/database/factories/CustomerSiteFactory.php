<?php

declare(strict_types=1);

namespace Modules\Customer\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\CustomerSite;

/**
 * @extends Factory<CustomerSite>
 */
class CustomerSiteFactory extends Factory
{
    /** @var class-string<CustomerSite> */
    protected $model = CustomerSite::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => CustomerFactory::new(),
            'name' => $this->faker->optional()->words(2, true),
            'address_line1' => $this->faker->optional()->streetAddress(),
            'city' => $this->faker->optional()->city(),
            'postal_code' => $this->faker->optional()->postcode(),
            'country_code' => 'IT',
        ];
    }
}
