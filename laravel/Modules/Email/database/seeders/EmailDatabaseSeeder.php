<?php

declare(strict_types=1);

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Email\Models\EmailConversation;
use Modules\Email\Models\EmailMessage;

class EmailDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        EmailConversation::factory()
            ->count(5)
            ->create()
            ->each(static function (EmailConversation $conversation): void {
                EmailMessage::factory()
                    ->count(3)
                    ->create(['conversation_id' => $conversation->id]);
            });
    }
}
