<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Database\Seeders;

use Illuminate\Database\Seeder;

class WhatsAppDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            WhatsAppContactIdentitySeeder::class,
            WhatsAppConversationSeeder::class,
            WhatsAppMessageSeeder::class,
        ]);
    }
}
