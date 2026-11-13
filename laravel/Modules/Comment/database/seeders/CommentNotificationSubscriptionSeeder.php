<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Console\Command;
use Modules\Comment\Models\CommentNotificationSubscription;

/**
 * Seeder per le sottoscrizioni notifica commenti.
 */
class CommentNotificationSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        CommentNotificationSubscription::query()->delete();

        if ($this->command instanceof Command) {
            $this->command->info('Comment notification subscriptions cleared.');
        }
    }
}
