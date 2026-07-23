<?php

declare(strict_types=1);

namespace Modules\Blog\Datas;

use Modules\Blog\Models\Article;
use Webmozart\Assert\Assert;

final class ArticleDataHydrator
{
    public static function findByUuid(string $uuid): Article
    {
        Assert::notNull($article = Article::where('uuid', $uuid)->first(), '['.__LINE__.']['.__FILE__.']');

        return $article;
    }
}
