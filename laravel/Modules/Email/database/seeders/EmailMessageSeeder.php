<?php

declare(strict_types=1);

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Email\Database\Factories\EmailConversationFactory;
use Modules\Email\Database\Factories\EmailMessageFactory;
use Modules\Email\Models\EmailConversation;
use Modules\Email\Models\EmailMessage;

class EmailMessageSeeder extends Seeder
{
    public function run(): void
    {
        if (EmailMessage::query()->exists()) {
            return;
        }

        $conversations = EmailConversation::query()->get();

        if ($conversations->isEmpty()) {
            $conversations = EmailConversationFactory::new()->count(3)->create();
        }

        foreach ($conversations as $conversation) {
            EmailMessageFactory::new()
                ->count(3)
                ->create(['conversation_id' => $conversation->id]);
        }
    }
}
