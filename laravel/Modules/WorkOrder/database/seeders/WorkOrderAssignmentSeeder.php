<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WorkOrder\Database\Factories\WorkOrderAssignmentFactory;
use Modules\WorkOrder\Models\WorkOrderAssignment;

class WorkOrderAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        if (WorkOrderAssignment::query()->exists()) {
            return;
        }

        WorkOrderAssignmentFactory::new()->count(2)->create();
    }
}
