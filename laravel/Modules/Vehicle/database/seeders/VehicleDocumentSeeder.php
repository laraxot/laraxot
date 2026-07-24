<?php

declare(strict_types=1);

namespace Modules\Vehicle\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Vehicle\Database\Factories\VehicleDocumentFactory;
use Modules\Vehicle\Models\VehicleDocument;

class VehicleDocumentSeeder extends Seeder
{
    public function run(): void
    {
        if (VehicleDocument::query()->exists()) {
            return;
        }

        VehicleDocumentFactory::new()->count(2)->create();
    }
}
