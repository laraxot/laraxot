<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TimberBilling\Models\TimberContactNote;
use Modules\TimberBilling\Models\TimberCustomer;

/**
 * @extends Factory<TimberContactNote>
 */
final class TimberContactNoteFactory extends Factory
{
    protected $model = TimberContactNote::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'noteable_type' => TimberCustomer::class,
            'noteable_id' => TimberCustomer::factory(),
            'body' => $this->faker->paragraph(),
            'created_by_user_id' => null,
        ];
    }
}
