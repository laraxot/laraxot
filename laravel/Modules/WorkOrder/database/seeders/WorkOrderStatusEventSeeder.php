<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WorkOrder\Database\Factories\WorkOrderStatusEventFactory;
use Modules\WorkOrder\Models\WorkOrderStatusEvent;

class WorkOrderStatusEventSeeder extends Seeder
{
    public function run(): void
    {
        if (WorkOrderStatusEvent::query()->exists()) {
            return;
        }

        WorkOrderStatusEventFactory::new()->count(2)->create();
    }
}
