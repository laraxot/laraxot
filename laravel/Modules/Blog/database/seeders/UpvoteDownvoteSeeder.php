<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\UpvoteDownvote;

class UpvoteDownvoteSeeder extends Seeder
{
    public function run(): void
    {
        xotSeedModelOnce(UpvoteDownvote::class);
    }
}
