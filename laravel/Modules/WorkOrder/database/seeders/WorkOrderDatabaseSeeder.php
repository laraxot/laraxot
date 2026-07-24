<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Database\Seeders;

use Illuminate\Database\Seeder;

class WorkOrderDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WorkOrderStatusSeeder::class,
            WorkOrderSeeder::class,
            WorkOrderAssignmentSeeder::class,
            WorkOrderActivitySeeder::class,
            WorkOrderStatusEventSeeder::class,
        ]);
    }
}
