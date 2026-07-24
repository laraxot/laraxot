<?php

declare(strict_types=1);

namespace Modules\Email\Database\Seeders;

use Illuminate\Database\Seeder;

class EmailDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EmailContactIdentitySeeder::class,
            EmailConversationSeeder::class,
            EmailMessageSeeder::class,
        ]);
    }
}
