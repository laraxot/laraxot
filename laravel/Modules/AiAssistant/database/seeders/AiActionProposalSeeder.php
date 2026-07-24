<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AiAssistant\Models\AiActionProposal;

class AiActionProposalSeeder extends Seeder
{
    public function run(): void
    {
        if (AiActionProposal::query()->exists()) {
            return;
        }

        AiActionProposal::factory()->count(2)->create();
    }
}
