<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Orchestratore Blog — N modelli owner = N {Model}Seeder (regola Laraxot).
 */
class BlogDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->command !== null) {
            $this->command->info('BlogDatabaseSeeder: entity seeders…');
        }

        // FixCity / Design Comuni: DemoContentSeeder = SSoT demo (testi civici,
        // foto locali, Comment morph). ArticleSeeder+CommentSeeder legacy
        // inquinano le liste FO — solo se BLOG_SEED_LEGACY=1.
        $this->call([
            ArticleCategorySeeder::class,
            BannerSeeder::class,
            CategorySeeder::class,
            CategoryPostSeeder::class,
            MenuSeeder::class,
            PostViewSeeder::class,
            ProfileSeeder::class,
            StatusSeeder::class,
            TagSeeder::class,
            TaggableSeeder::class,
            TextWidgetSeeder::class,
            TransactionSeeder::class,
            UpvoteDownvoteSeeder::class,
        ]);

        if (filter_var(config('env.BLOG_SEED_LEGACY', false), FILTER_VALIDATE_BOOLEAN)) {
            $this->call([
                CommentSeeder::class,
                ArticleSeeder::class,
            ]);
        }

        $this->call([
            DemoContentSeeder::class,
        ]);

        if ($this->command !== null) {
            $this->command->info('BlogDatabaseSeeder: completato.');
        }
    }
}
