<?php

declare(strict_types=1);

namespace Modules\Blog\Datas;

use Spatie\LaravelData\Data;

class ArticleDataBlocks extends Data
{
    /**
     * @param array<int|string, mixed>|null $contentBlocks
     * @param array<int|string, mixed>|null $sidebarBlocks
     * @param array<int|string, mixed>|null $footerBlocks
     */
    public function __construct(
        public ?array $contentBlocks,
        public ?array $sidebarBlocks,
        public ?array $footerBlocks,
    ) {
    }
}
