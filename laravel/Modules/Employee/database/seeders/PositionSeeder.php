<?php

declare(strict_types=1);

namespace Modules\Employee\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Employee\Database\Factories\PositionFactory;
use Modules\Employee\Models\Position;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        if (Position::query()->exists()) {
            return;
        }

        PositionFactory::new()->count(12)->create();
    }
}
