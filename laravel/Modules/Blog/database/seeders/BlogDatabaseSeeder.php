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
        if (null !== $this->command) {
            $this->command->info('BlogDatabaseSeeder: entity seeders…');
        }

        $this->call([
            ArticleSeeder::class,
            ArticleCategorySeeder::class,
            BannerSeeder::class,
            CategorySeeder::class,
            CategoryPostSeeder::class,
            CommentSeeder::class,
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

        if (null !== $this->command) {
            $this->command->info('BlogDatabaseSeeder: completato.');
        }
    }
}
