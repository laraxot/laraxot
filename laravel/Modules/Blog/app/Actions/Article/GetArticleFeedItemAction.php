<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Modules\Blog\Models\Article;
use Spatie\Feed\FeedItem;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

final class GetArticleFeedItemAction
{
    use QueueableAction;

    public function execute(Article $article): FeedItem
    {
        Assert::notNull($article->user, '['.__LINE__.']['.__FILE__.']');

        return FeedItem::create()
            ->id($article->slug)
            ->title($article->title)
            ->summary($article->description)
            ->updated($article->updated_at)
            ->authorName($article->user->name ?? 'Unknown');
    }
}
