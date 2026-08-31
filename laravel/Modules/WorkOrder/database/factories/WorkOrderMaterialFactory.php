<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\WorkOrder\Models\WorkOrderMaterial;

/**
 * @extends Factory<WorkOrderMaterial>
 */
class WorkOrderMaterialFactory extends Factory
{
    /** @var class-string<WorkOrderMaterial> */
    protected $model = WorkOrderMaterial::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
