<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WorkOrder\Database\Factories\WorkOrderActivityFactory;
use Modules\WorkOrder\Models\WorkOrderActivity;

class WorkOrderActivitySeeder extends Seeder
{
    public function run(): void
    {
        if (WorkOrderActivity::query()->exists()) {
            return;
        }

        WorkOrderActivityFactory::new()->count(2)->create();
    }
}
