<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Modules\Blog\Models\Article;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

final class FormatArticlePublishedDateAction
{
    use QueueableAction;

    public function execute(Article $article): string
    {
        Assert::notNull($article->published_at, '['.__LINE__.']['.__FILE__.']');

        return $article->published_at->format('F jS Y');
    }
}
