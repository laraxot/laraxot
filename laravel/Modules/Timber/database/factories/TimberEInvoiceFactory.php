<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Timber\Models\TimberEInvoice;
use Modules\Timber\Models\TimberInvoice;

/**
 * @extends Factory<TimberEInvoice>
 */
final class TimberEInvoiceFactory extends Factory
{
    protected $model = TimberEInvoice::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => TimberInvoice::factory(),
            'direction' => 'out',
            'sdi_status' => 'da_inviare',
        ];
    }
}
