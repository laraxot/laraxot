<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AiAssistant\Models\AiToolLog;

class AiToolLogSeeder extends Seeder
{
    public function run(): void
    {
        if (AiToolLog::query()->exists()) {
            return;
        }

        AiToolLog::factory()->count(2)->create();
    }
}
