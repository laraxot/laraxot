<?php

declare(strict_types=1);

namespace Modules\Notify\Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
<<<<<<< HEAD
<<<<<<< HEAD
            MailTemplateSeeder::class]);
=======
            MailTemplateSeeder::class,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            MailTemplateSeeder::class]);
>>>>>>> a988596b (first)
    }
}
