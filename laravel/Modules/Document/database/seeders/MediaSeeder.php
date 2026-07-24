<?php

declare(strict_types=1);

namespace Modules\Document\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Document\Models\Media;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        if (Media::query()->exists()) {
            return;
        }

        Media::factory()->count(2)->create();
    }
}
