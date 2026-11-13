<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Console\Command;
use Modules\Comment\Models\Reaction;

/**
 * Seeder per le reazioni (like/dislike) ai commenti.
 */
class ReactionSeeder extends Seeder
{
    public function run(): void
    {
        Reaction::query()->delete();

        if ($this->command instanceof Command) {
            $this->command->info('Reactions cleared - will be seeded with comments.');
        }
    }
}
