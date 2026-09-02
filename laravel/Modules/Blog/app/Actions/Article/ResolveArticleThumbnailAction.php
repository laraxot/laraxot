<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Modules\Blog\Models\Article;
use Spatie\QueueableAction\QueueableAction;

final class ResolveArticleThumbnailAction
{
    use QueueableAction;

    public function execute(Article $article): string
    {
        if (null !== $article->getMedia()->first()) {
            return $article->getMedia()->first()->getUrl();
        }

        return '#';
    }
}
