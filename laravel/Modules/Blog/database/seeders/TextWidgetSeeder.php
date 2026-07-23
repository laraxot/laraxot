<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\TextWidget;

class TextWidgetSeeder extends Seeder
{
    public function run(): void
    {
        xotSeedModelOnce(TextWidget::class);
    }
}
