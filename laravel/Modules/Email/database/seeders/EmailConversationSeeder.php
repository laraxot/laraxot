<?php

declare(strict_types=1);

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Email\Database\Factories\EmailConversationFactory;
use Modules\Email\Models\EmailConversation;

class EmailConversationSeeder extends Seeder
{
    public function run(): void
    {
        if (EmailConversation::query()->exists()) {
            return;
        }

        EmailConversationFactory::new()->count(5)->create();
    }
}
