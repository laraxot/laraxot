<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Seeders;

use Illuminate\Database\Seeder;

class AiAssistantDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AiThreadSeeder::class,
            AiMessageSeeder::class,
            AiActionProposalSeeder::class,
            AiToolLogSeeder::class,
        ]);
    }
}
