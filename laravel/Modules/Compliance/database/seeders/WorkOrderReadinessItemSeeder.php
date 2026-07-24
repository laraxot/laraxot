<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Compliance\Database\Factories\WorkOrderReadinessItemFactory;
use Modules\Compliance\Models\WorkOrderReadinessItem;

class WorkOrderReadinessItemSeeder extends Seeder
{
    public function run(): void
    {
        if (WorkOrderReadinessItem::query()->exists()) {
            return;
        }

        WorkOrderReadinessItemFactory::new()->count(2)->create();
    }
}
