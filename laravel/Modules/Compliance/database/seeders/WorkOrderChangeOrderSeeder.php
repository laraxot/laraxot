<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\WorkOrderChangeOrderFactory;
use Modules\Compliance\Models\WorkOrderChangeOrder;

class WorkOrderChangeOrderSeeder extends Seeder
{
    public function run(): void
    {
        if (WorkOrderChangeOrder::query()->exists()) {
            return;
        }

        WorkOrderChangeOrderFactory::new()->count(2)->create();
    }
}
