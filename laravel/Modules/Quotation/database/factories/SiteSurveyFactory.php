<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Models\SiteSurvey;

/**
 * @extends Factory<SiteSurvey>
 */
class SiteSurveyFactory extends Factory
{
    /** @var class-string<SiteSurvey> */
    protected $model = SiteSurvey::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'SS-'.$this->faker->unique()->numerify('####'),
            'customer_id' => Customer::factory(),
            'customer_site_id' => null,
            'work_order_id' => null,
            'quotation_id' => null,
            'performed_by_user_id' => null,
            'title' => $this->faker->optional()->sentence(3),
            'notes' => $this->faker->optional()->paragraph(),
            'status_code' => 'planned',
            'scheduled_at' => now()->addDay(),
            'performed_at' => null,
        ];
    }
}
