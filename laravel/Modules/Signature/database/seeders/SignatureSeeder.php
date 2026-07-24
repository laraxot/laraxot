<?php

declare(strict_types=1);

namespace Modules\Signature\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Signature\Database\Factories\SignatureFactory;
use Modules\Signature\Models\Signature;

class SignatureSeeder extends Seeder
{
    public function run(): void
    {
        if (Signature::query()->exists()) {
            return;
        }

        SignatureFactory::new()->count(2)->create();
    }
}
