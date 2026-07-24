<?php

declare(strict_types=1);

namespace Modules\AI\Database\Seeders;

use Illuminate\Database\Seeder;

class AIDatabaseSeeder extends Seeder
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
