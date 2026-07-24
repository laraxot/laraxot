<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WorkOrder\Database\Factories\WorkOrderFactory;
use Modules\WorkOrder\Models\WorkOrder;

class WorkOrderSeeder extends Seeder
{
    public function run(): void
    {
        if (WorkOrder::query()->exists()) {
            return;
        }

        WorkOrderFactory::new()->count(2)->create();
    }
}
