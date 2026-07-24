<?php

declare(strict_types=1);

namespace Modules\Intervention\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Intervention\Database\Factories\InterventionUserAssignmentFactory;
use Modules\Intervention\Models\InterventionUserAssignment;

class InterventionUserAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        if (InterventionUserAssignment::query()->exists()) {
            return;
        }

        InterventionUserAssignmentFactory::new()->count(2)->create();
    }
}
