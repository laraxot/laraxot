<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\Status;

/**
 * Status Spatie demo su primo articolo — schema blog_statuses (name, reason, morph).
 */
class StatusSeeder extends Seeder
{
    public function run(): void
    {
        $article = Article::query()->first();
        if (null === $article) {
            return;
        }

        Status::query()->firstOrCreate(
            [
                'name' => 'published',
                'model_type' => $article->getMorphClass(),
                'model_id' => $article->getKey(),
            ],
            [
                'reason' => 'Seeder demo Blog',
            ],
        );
    }
}
