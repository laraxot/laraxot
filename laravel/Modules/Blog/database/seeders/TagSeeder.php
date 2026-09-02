<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\Tag;

/**
 * Tag blog demo — schema blog_tags (name/slug JSON Spatie, type, order_column).
 */
class TagSeeder extends Seeder
{
    /** @var list<array{name: array{it: string, en: string}, type: string}> */
    private const TAGS = [
        [
            'name' => ['it' => 'Mercati', 'en' => 'Markets'],
            'type' => 'predict',
        ],
        [
            'name' => ['it' => 'Analisi', 'en' => 'Analysis'],
            'type' => 'predict',
        ],
        [
            'name' => ['it' => 'Tutorial', 'en' => 'Tutorial'],
            'type' => 'blog',
        ],
    ];

    public function run(): void
    {
        foreach (self::TAGS as $index => $tag) {
            $model = Tag::findOrCreate($tag['name'], $tag['type']);
            $model->update(['order_column' => $index + 1]);
        }
    }
}
