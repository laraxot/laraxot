<?php

declare(strict_types=1);

namespace Modules\Signature\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Signature\Database\Factories\SketchFactory;
use Modules\Signature\Models\Sketch;

class SketchSeeder extends Seeder
{
    public function run(): void
    {
        if (Sketch::query()->exists()) {
            return;
        }

        SketchFactory::new()->count(2)->create();
    }
}
