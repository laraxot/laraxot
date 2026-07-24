<?php

declare(strict_types=1);

namespace Modules\Document\Database\Seeders;

use Illuminate\Database\Seeder;

class DocumentDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            MediaSeeder::class,
        ]);
    }
}
