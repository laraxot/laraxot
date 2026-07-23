<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\Banner;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        xotSeedModelOnce(Banner::class);
    }
}
