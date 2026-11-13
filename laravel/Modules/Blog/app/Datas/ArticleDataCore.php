<?php

declare(strict_types=1);

namespace Modules\Blog\Datas;

use Spatie\LaravelData\Data;

class ArticleDataCore extends Data
{
    public function __construct(
        public string $id,
        public string $uuid,
        public string $slug,
        public ?int $categoryId,
        public ?string $status,
        public bool $showOnHomepage,
        public ?string $publishedAt,
        public ?string $url,
        public ?string $closedAt,
    ) {
    }
}
