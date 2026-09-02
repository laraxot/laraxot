<?php

declare(strict_types=1);

namespace Modules\Blog\View\Composers\Support;

use Illuminate\Support\Collection;
use Modules\Blog\Datas\ArticleData;
use Modules\Blog\Models\Article;
use Webmozart\Assert\Assert;

final class ThemeArticleDataMapper
{
    public function map(object $article): ArticleData
    {
        if ($article instanceof ArticleData) {
            return $article;
        }

        Assert::isInstanceOf($article, Article::class);

        $payload = $article->toArray();
        if (is_array($payload['title'] ?? null)) {
            $lang = app()->getLocale();
            $payload['title'] = $payload['title'][$lang] ?? last($payload['title']);
        }

        return ArticleData::from($payload);
    }

    /**
     * @param Collection<int, Article> $rows
     *
     * @return list<ArticleData>
     */
    public function fromCollection(Collection $rows): array
    {
        $tmp = [];
        foreach ($rows->toArray() as $content) {
            /** @var array<mixed> $content */
            if (isset($content['title']) && is_array($content['title'])) {
                $lang = app()->getLocale();
                $content['title'] = $content['title'][$lang] ?? last($content['title']);
            }

            $tmp[] = ArticleData::from($content);
        }

        return $tmp;
    }
}
