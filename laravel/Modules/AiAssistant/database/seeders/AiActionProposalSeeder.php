<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\AiAssistant\Database\Factories\AiActionProposalFactory;
use Modules\AiAssistant\Models\AiActionProposal;

class AiActionProposalSeeder extends Seeder
{
    public function run(): void
    {
        if (AiActionProposal::query()->exists()) {
            return;
        }

        AiActionProposalFactory::new()->count(2)->create();
    }
}
