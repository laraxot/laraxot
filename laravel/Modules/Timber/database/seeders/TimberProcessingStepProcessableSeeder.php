<?php

declare(strict_types=1);

namespace Modules\Timber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Timber\Models\TimberProcessingStepProcessable;

final class TimberProcessingStepProcessableSeeder extends Seeder
{
    public function run(): void
    {
        if (TimberProcessingStepProcessable::query()->exists()) {
            return;
        }

        TimberProcessingStepProcessable::factory()->count(3)->create();
    }
}
