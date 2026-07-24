<?php

declare(strict_types=1);

namespace Modules\Bom\Database\Seeders;

use Illuminate\Database\Seeder;

class BomDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            BomSeeder::class,
            BomItemSeeder::class,
        ]);
    }
}
