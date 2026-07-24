<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WorkOrder\Database\Factories\WorkOrderStatusFactory;
use Modules\WorkOrder\Models\WorkOrderStatus;

class WorkOrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        if (WorkOrderStatus::query()->exists()) {
            return;
        }

        WorkOrderStatusFactory::new()->count(2)->create();
    }
}
