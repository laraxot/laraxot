<?php

declare(strict_types=1);

namespace Modules\Billing\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Billing\Models\InvoiceWorkOrderLink;

/**
 * @extends Factory<InvoiceWorkOrderLink>
 */
class InvoiceWorkOrderLinkFactory extends Factory
{
    /** @var class-string<InvoiceWorkOrderLink> */
    protected $model = InvoiceWorkOrderLink::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
