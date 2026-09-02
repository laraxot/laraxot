<?php

declare(strict_types=1);

namespace Modules\Blog\Datas;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Modules\Blog\Models\Category;
use Spatie\LaravelData\Data;

final class ArticleDataHydrated extends Data
{
    /**
     * @param EloquentCollection<int, Category>|null $categories
     * @param array<int, array<string, mixed>>|null  $ratings
     * @param Collection<int, string>|null           $tags
     */
    public function __construct(
        public ?EloquentCollection $categories = null,
        public ?array $ratings = null,
        public ?string $closedAtDate = null,
        public ?string $timeLeftForHumans = null,
        public ?Collection $tags = null,
    ) {
    }
}
