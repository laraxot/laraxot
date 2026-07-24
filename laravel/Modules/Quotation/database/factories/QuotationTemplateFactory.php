<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Quotation\Models\QuotationTemplate;

/**
 * @extends Factory<QuotationTemplate>
 */
class QuotationTemplateFactory extends Factory
{
    /** @var class-string<QuotationTemplate> */
    protected $model = QuotationTemplate::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->words(3, true),
            'notes' => null,
        ];
    }
}
