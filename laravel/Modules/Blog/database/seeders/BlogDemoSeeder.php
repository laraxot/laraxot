<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Database\Factories\ArticleFactory;
use Modules\Blog\Database\Factories\CategoryFactory;

class BlogDemoSeeder extends Seeder
{
    public function run(): void
    {
        // BMAD + Second Brain: Demo contenuto blog per investitore (high parity DesignComuni)
        CategoryFactory::new()->count(4)->create();

        ArticleFactory::new()->count(3)->create([
            'type' => 'post',
            'is_featured' => true,
            'show_on_homepage' => true,
            'published_at' => now()->subDays(2),
        ]);

        ArticleFactory::new()->count(2)->create([
            'type' => 'event',
            'is_featured' => true,
            'published_at' => now()->subDay(),
        ]);
    }
}
