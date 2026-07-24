<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WhatsApp\Database\Factories\WhatsAppConversationFactory;
use Modules\WhatsApp\Models\WhatsAppConversation;

class WhatsAppConversationSeeder extends Seeder
{
    public function run(): void
    {
        if (WhatsAppConversation::query()->exists()) {
            return;
        }

        WhatsAppConversationFactory::new()->count(5)->create();
    }
}
