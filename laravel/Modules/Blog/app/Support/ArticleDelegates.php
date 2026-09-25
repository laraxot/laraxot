<?php

declare(strict_types=1);

namespace Modules\Blog\Support;

use Illuminate\Support\Facades\Storage;
use Modules\Blog\Models\Article;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\Feed\FeedItem;
use Webmozart\Assert\Assert;

final class ArticleDelegates
{
    /**
     * @return array<int|string, mixed>|string|int|null
     */
    public static function translation(Article $article, string $key, string $locale, bool $useFallbackLocale): array|string|int|null
    {
        return app(ArticleTranslationResolver::class)->resolve($article, $key, $locale, $useFallbackLocale);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function humanReadTime(array $attributes): string
    {
        return app(ArticleReadTimeFormatter::class)->fromAttributes($attributes);
    }

    public static function timeLeftForHumans(Article $article): string
    {
        return app(ArticleTimeLeftFormatter::class)->forHumans($article);
    }

    /**
     * @param array<int, string> $nameBlocks
     *
     * @return array<int, array<string, mixed>>
     */
    public static function onlyContentBlocks(Article $article, array $nameBlocks): array
    {
        return app(ArticleContentBlockFilter::class)->only($article, $nameBlocks);
    }

    /**
     * @param array<int, string> $nameBlocks
     *
     * @return array<int, array<string, mixed>>
     */
    public static function exceptContentBlocks(Article $article, array $nameBlocks): array
    {
        return app(ArticleContentBlockFilter::class)->except($article, $nameBlocks);
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public static function mainImage(array $attributes): string
    {
        return app(ArticleMainImageResolver::class)->fromAttributes($attributes);
    }

    public static function toFeedItem(Article $article): FeedItem
    {
        Assert::notNull($article->user, '['.__LINE__.']['.__FILE__.']');
        Assert::notNull($article->updated_at, '['.__LINE__.']['.__FILE__.']');

        return FeedItem::create()
            ->id(SafeStringCastAction::cast($article->slug))
            ->title(SafeStringCastAction::cast($article->title))
            ->summary(SafeStringCastAction::cast($article->description))
            ->updated($article->updated_at)
            ->authorName($article->user->name ?? 'Unknown');
    }

    public static function formattedDate(Article $article): string
    {
        Assert::notNull($article->published_at, '['.__LINE__.']['.__FILE__.']');

        return $article->published_at->format('F jS Y');
    }

    public static function thumbnail(Article $article): string
    {
        if (null !== $article->getMedia()->first()) {
            return $article->getMedia()->first()->getUrl();
        }

        return '#';
    }

    public static function mainImageUrl(Article $article): string
    {
        if ($article->media) {
            return $article->getFirstMediaUrl('main_image_upload');
        }

        if ($article->main_image_upload) {
            return Storage::url(SafeStringCastAction::cast($article->main_image_upload));
        }

        if (null !== $article->main_image_url) {
            return SafeStringCastAction::cast($article->main_image_url);
        }

        return '#';
    }
}
