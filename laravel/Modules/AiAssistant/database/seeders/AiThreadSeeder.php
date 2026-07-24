<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AiAssistant\Models\AiThread;

class AiThreadSeeder extends Seeder
{
    public function run(): void
    {
        if (AiThread::query()->exists()) {
            return;
        }

        AiThread::factory()->count(2)->create();
    }
}
