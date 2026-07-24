<?php

declare(strict_types=1);

namespace Modules\Quotation\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Quotation\Models\QuotationTemplate;
use Modules\Quotation\Models\QuotationTemplateItem;

/**
 * @extends Factory<QuotationTemplateItem>
 */
class QuotationTemplateItemFactory extends Factory
{
    /** @var class-string<QuotationTemplateItem> */
    protected $model = QuotationTemplateItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quotation_template_id' => QuotationTemplate::factory(),
            'description' => $this->faker->sentence(),
            'quantity' => 1,
            'unit_price' => $this->faker->randomFloat(2, 10, 200),
        ];
    }
}
