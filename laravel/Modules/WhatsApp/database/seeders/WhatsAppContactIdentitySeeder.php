<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WhatsApp\Database\Factories\WhatsAppContactIdentityFactory;
use Modules\WhatsApp\Models\WhatsAppContactIdentity;

class WhatsAppContactIdentitySeeder extends Seeder
{
    public function run(): void
    {
        if (WhatsAppContactIdentity::query()->exists()) {
            return;
        }

        WhatsAppContactIdentityFactory::new()->count(5)->create();
    }
}
