<?php

declare(strict_types=1);

namespace Modules\Blog\Adapters;

use Modules\Blog\Actions\Article\ConvertArticleToFeedItemAction;
use Modules\Blog\Actions\Article\FilterArticleContentBlocksExceptAction;
use Modules\Blog\Actions\Article\FilterArticleContentBlocksOnlyAction;
use Modules\Blog\Actions\Article\FormatArticleHumanReadTimeAction;
use Modules\Blog\Actions\Article\FormatArticlePublishedDateAction;
use Modules\Blog\Actions\Article\FormatArticleTimeLeftForHumansAction;
use Modules\Blog\Actions\Article\ResolveArticleMainImageFromAttributesAction;
use Modules\Blog\Actions\Article\ResolveArticleMainImageUrlAction;
use Modules\Blog\Actions\Article\ResolveArticleThumbnailAction;
use Modules\Blog\Actions\Article\ResolveArticleTranslationAction;
use Modules\Blog\Models\Article;
use Spatie\Feed\FeedItem;

/**
 * Adapter del modello Article: coordina metodi legacy e delega ogni use case alle QueueableAction.
 */
final class ArticlePresentationAdapter
{
    /**
     * @return array<int|string, mixed>|string|int|null
     */
    public function translation(
        Article $article,
        string $key,
        string $locale,
        bool $useFallbackLocale,
    ): array|string|int|null {
        return app(ResolveArticleTranslationAction::class)->execute($article, $key, $locale, $useFallbackLocale);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function humanReadTime(array $attributes): string
    {
        return app(FormatArticleHumanReadTimeAction::class)->execute($attributes);
    }

    public function timeLeftForHumans(Article $article): string
    {
        return app(FormatArticleTimeLeftForHumansAction::class)->execute($article);
    }

    /**
     * @param array<int, string> $nameBlocks
     *
     * @return array<int, array<string, mixed>>
     */
    public function onlyContentBlocks(Article $article, array $nameBlocks): array
    {
        return app(FilterArticleContentBlocksOnlyAction::class)->execute($article, $nameBlocks);
    }

    /**
     * @param array<int, string> $nameBlocks
     *
     * @return array<int, array<string, mixed>>
     */
    public function exceptContentBlocks(Article $article, array $nameBlocks): array
    {
        return app(FilterArticleContentBlocksExceptAction::class)->execute($article, $nameBlocks);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function mainImage(array $attributes): string
    {
        return app(ResolveArticleMainImageFromAttributesAction::class)->execute($attributes);
    }

    public function toFeedItem(Article $article): FeedItem
    {
        return app(ConvertArticleToFeedItemAction::class)->execute($article);
    }

    public function formattedDate(Article $article): string
    {
        return app(FormatArticlePublishedDateAction::class)->execute($article);
    }

    public function thumbnail(Article $article): string
    {
        return app(ResolveArticleThumbnailAction::class)->execute($article);
    }

    public function mainImageUrl(Article $article): string
    {
        return app(ResolveArticleMainImageUrlAction::class)->execute($article);
    }
}
