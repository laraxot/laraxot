<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Comment\Models\Reaction;

/**
 * Seeder per le reazioni (like/dislike) ai commenti.
 */
class ReactionSeeder extends Seeder
{
    public function run(): void
    {
        Reaction::query()->delete();

        $this->command->info('Reactions cleared - will be seeded with comments.');
    }
}