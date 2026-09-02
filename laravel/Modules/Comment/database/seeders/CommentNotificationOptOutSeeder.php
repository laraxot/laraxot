<?php

declare(strict_types=1);

namespace Modules\Comment\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Comment\Models\CommentNotificationOptOut;

class CommentNotificationOptOutSeeder extends Seeder
{
    public function run(): void
    {
        xotSeedModelOnce(CommentNotificationOptOut::class);
    }
}
