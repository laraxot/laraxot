<?php

declare(strict_types=1);

namespace Modules\Blog\Models\Concerns;

use Modules\Blog\Actions\Article\GetArticleFeedItemAction;
use Spatie\Feed\FeedItem;

trait ArticleFeedable
{
    public function toFeedItem(): FeedItem
    {
        return app(GetArticleFeedItemAction::class)->execute($this);
    }
}
