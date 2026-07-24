<?php

declare(strict_types=1);

namespace Modules\Signature\Database\Seeders;

use Illuminate\Database\Seeder;

class SignatureDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SignatureSeeder::class,
            SketchSeeder::class,
        ]);
    }
}
