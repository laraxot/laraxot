<?php

declare(strict_types=1);

namespace Modules\Blog\Actions\Article;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\Category;

use function Safe\json_decode;

use Spatie\MediaLibrary\MediaCollections\FileAdder;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class ImportArticlesFromByJsonTextAction
{
    use QueueableAction;

    public function execute(string $jsonText): void
    {
        /** @var array<mixed> $json */
        $json = json_decode($jsonText, true);
        Assert::isArray($json, '['.__LINE__.']['.__FILE__.']');

        foreach ($json as $item) {
            Assert::isArray($item, 'Each element must be an array');
            $this->importArticle($item);
        }
    }

    /**
     * @param array<mixed> $item
     */
    private function importArticle(array $item): void
    {
        $parentCategoryId = $this->importCategories($item);
        $article = $this->upsertArticle($item, $parentCategoryId);
        $this->importRatings($article, $item);
    }

    /**
     * @param array<mixed> $item
     */
    private function importCategories(array $item): ?int
    {
        $parentCategoryId = null;
        /** @var array<mixed> $categories */
        $categories = $item['category'] ?? [];
        Assert::isArray($categories, 'Category must be an array');

        foreach ($categories as $categoryItem) {
            Assert::isArray($categoryItem, 'Category item must be an array');
            $parentCategoryId = $this->upsertCategory($categoryItem, $parentCategoryId);
        }

        return $parentCategoryId;
    }

    /**
     * @param array<mixed> $categoryItem
     */
    private function upsertCategory(array $categoryItem, ?int $parentCategoryId): int
    {
        $categoryData = [
            'title' => $categoryItem['title'] ?? '',
            'slug' => $categoryItem['slug'] ?? '',
            'parent_id' => $parentCategoryId,
        ];
        $categoryWhere = ['slug' => $categoryData['slug']];
        /** @var Category $category */
        $category = Category::firstOrCreate($categoryWhere, $categoryData);

        return (int) $category->id;
    }

    /**
     * @param array<mixed> $item
     */
    private function upsertArticle(array $item, ?int $parentCategoryId): Article
    {
        $articleWhere = [
            'slug' => $item['slug'],
        ];

        $articleData = [
            'uuid' => Str::uuid(),
            'title' => $item['title'],
            'slug' => $item['slug'],
            'status' => $item['status'],
            'status_display' => 'open' === $item['status_display'],
            'bet_end_date' => $this->parseOptionalDate($item['bet_end_date'] ?? ''),
            'event_start_date' => $this->parseOptionalDate($item['event_start_date'] ?? ''),
            'event_end_date' => $this->parseOptionalDate($item['event_end_date'] ?? ''),
            'is_wagerable' => $item['is_wagerable'],
            'brier_score' => $item['brier_score'],
            'brier_score_play_money' => $item['brier_score_play_money'],
            'brier_score_real_money' => $item['brier_score_real_money'],
            'wagers_count' => $item['wagers_count'],
            'wagers_count_canonical' => $item['wagers_count_canonical'],
            'wagers_count_total' => $item['wagers_count_total'],
            'wagers' => $item['wagers'],
            'volume_play_money' => $item['volume_play_money'],
            'volume_real_money' => $item['volume_real_money'],
            'is_following' => $item['volume_real_money'],
            'category_id' => $parentCategoryId,
            'published_at' => Carbon::today()->toDateString(),
        ];

        /** @var Article $article */
        $article = Article::firstOrCreate($articleWhere, $articleData);

        return $article;
    }

    /**
     * @param array<mixed> $item
     */
    private function importRatings(Article $article, array $item): void
    {
        /** @var array<int, array<string, mixed>> $outcomes */
        $outcomes = $item['outcomes'];
        foreach ($outcomes as $rating) {
            if (! is_array($rating)) {
                continue;
            }

            $this->upsertRating($article, $rating);
        }
    }

    /**
     * @param array<string, mixed> $rating
     */
    private function upsertRating(Article $article, array $rating): void
    {
        $ratingTitle = is_string($rating['title'] ?? null) ? $rating['title'] : '';
        $ratingWhere = ['title' => $ratingTitle];
        $ratingData = [
            'title' => $ratingTitle,
            'is_disabled' => (bool) ($rating['disabled'] ?? false),
        ];

        $ratingModel = $article->ratings()->firstOrCreate($ratingWhere, $ratingData);
        if (! isset($rating['thumbnail_2x']) || ! is_string($rating['thumbnail_2x'])) {
            return;
        }

        if (! method_exists($ratingModel, 'addMediaFromUrl')) {
            return;
        }

        $fileAdder = $ratingModel->addMediaFromUrl($rating['thumbnail_2x']);
        Assert::isInstanceOf($fileAdder, FileAdder::class);
        $fileAdder->toMediaCollection('rating');
    }

    private function parseOptionalDate(mixed $value): CarbonInterface|string
    {
        if (! \is_string($value) || mb_strlen($value) <= 3) {
            return \is_string($value) ? $value : '';
        }

        return Carbon::parse($value);
    }
}
