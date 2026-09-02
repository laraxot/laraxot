<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Modules\Blog\Models\Article;
use Modules\Xot\Actions\Cast\SafeStringCastAction;
use Spatie\Feed\FeedItem;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

final class ConvertArticleToFeedItemAction
{
    use QueueableAction;

    public function execute(Article $article): FeedItem
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
}
