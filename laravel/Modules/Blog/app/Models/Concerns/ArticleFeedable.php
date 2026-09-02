<?php

declare(strict_types=1);

namespace Modules\Blog\Models\Concerns;

use Modules\Blog\Actions\Article\ConvertArticleToFeedItemAction;
use Spatie\Feed\FeedItem;

trait ArticleFeedable
{
    public function toFeedItem(): FeedItem
    {
        return app(ConvertArticleToFeedItemAction::class)->execute($this);
    }
}
