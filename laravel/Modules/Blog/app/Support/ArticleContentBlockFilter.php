<?php

declare(strict_types=1);

namespace Modules\Blog\Support;

use Modules\Blog\Models\Article;

final class ArticleContentBlockFilter
{
    /**
     * @param array<int, string> $nameBlocks
     *
     * @return array<int, array<string, mixed>>
     */
    public function only(Article $article, array $nameBlocks): array
    {
        /** @var array<int, array<string, mixed>> $contentBlocks */
        $contentBlocks = is_array($article->content_blocks) ? $article->content_blocks : [];

        return collect($contentBlocks)
            ->filter(fn (array $value): bool => $this->matchesAnyType($value, $nameBlocks))
            ->values()
            ->all();
    }

    /**
     * @param array<int, string> $nameBlocks
     *
     * @return array<int, array<string, mixed>>
     */
    public function except(Article $article, array $nameBlocks): array
    {
        /** @var array<int, array<string, mixed>> $contentBlocks */
        $contentBlocks = is_array($article->content_blocks) ? $article->content_blocks : [];

        return collect($contentBlocks)
            ->filter(fn (array $value): bool => ! $this->matchesAnyType($value, $nameBlocks))
            ->values()
            ->all();
    }

    /**
     * @param array<string, mixed> $value
     * @param array<int, string>   $nameBlocks
     */
    private function matchesAnyType(array $value, array $nameBlocks): bool
    {
        foreach ($nameBlocks as $block) {
            if (($value['type'] ?? null) === $block) {
                return true;
            }
        }

        return false;
    }
}
