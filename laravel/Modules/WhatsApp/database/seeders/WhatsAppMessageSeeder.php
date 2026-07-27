<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\WhatsApp\Database\Factories\WhatsAppConversationFactory;
use Modules\WhatsApp\Database\Factories\WhatsAppMessageFactory;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;

class WhatsAppMessageSeeder extends Seeder
{
    public function run(): void
    {
        if (WhatsAppMessage::query()->exists()) {
            return;
        }

        $conversations = WhatsAppConversation::query()->get();

        if ($conversations->isEmpty()) {
            WhatsAppConversationFactory::new()->count(3)->create();
            $conversations = WhatsAppConversation::query()->get();
        }

        foreach ($conversations as $conversation) {
            WhatsAppMessageFactory::new()
                ->count(3)
                ->create(['conversation_id' => $conversation->id]);
        }
    }
}
