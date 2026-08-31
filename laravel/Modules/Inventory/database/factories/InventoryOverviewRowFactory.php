<?php

declare(strict_types=1);

namespace Modules\Inventory\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Inventory\Models\InventoryOverviewRow;

/**
 * @extends Factory<InventoryOverviewRow>
 */
class InventoryOverviewRowFactory extends Factory
{
    /** @var class-string<InventoryOverviewRow> */
    protected $model = InventoryOverviewRow::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
