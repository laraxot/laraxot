<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberDocument;

/**
 * @extends Factory<TimberDocument>
 */
final class TimberDocumentFactory extends Factory
{
    protected $model = TimberDocument::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_type_code' => 'ddt',
            'number' => strtoupper($this->faker->unique()->bothify('DOC-2026-####')),
            'document_date' => now()->toDateString(),
            'status_code' => 'draft',
            'total_amount' => 0,
        ];
    }
}
