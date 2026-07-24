<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;

class WhatsAppDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        WhatsAppConversation::factory()
            ->count(5)
            ->create()
            ->each(static function (WhatsAppConversation $conversation): void {
                WhatsAppMessage::factory()
                    ->count(3)
                    ->create(['conversation_id' => $conversation->id]);
            });
    }
}
