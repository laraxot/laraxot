<?php

declare(strict_types=1);

namespace Modules\Blog\Datas;

use Carbon\Carbon;
use Modules\Blog\Actions\Category\GetBloodline;
use Spatie\LaravelData\Data;

class ArticleDataCore extends Data
{
    public function __construct(
        public string $id,
        public string $uuid,
        public string $slug,
        public ?int $categoryId,
        public ?string $status,
        public bool $showOnHomepage,
        public ?string $publishedAt,
        public ?string $url,
        public ?string $closedAt,
    ) {
    }
}

class ArticleDataBlocks extends Data
{
    /**
     * @param array<int|string, mixed>|null $contentBlocks
     * @param array<int|string, mixed>|null $sidebarBlocks
     * @param array<int|string, mixed>|null $footerBlocks
     */
    public function __construct(
        public ?array $contentBlocks,
        public ?array $sidebarBlocks,
        public ?array $footerBlocks,
    ) {
    }
}

/**
 * @phpstan-consistent-constructor
 */
class ArticleData extends Data implements \Stringable
{
    public string $title;

    public string $id;

    public string $uuid;

    public string $slug;

    public ?int $categoryId;

    public ?string $status;

    public bool $showOnHomepage;

    public ?string $publishedAt;

    /** @var array<int|string, mixed>|null */
    public ?array $contentBlocks;

    /** @var array<int|string, mixed>|null */
    public ?array $sidebarBlocks;

    /** @var array<int|string, mixed>|null */
    public ?array $footerBlocks;

    public ?string $url;

    public ?string $closedAt;

    public ArticleDataHydrated $hydrated;

    /**
     * @param array<int|string, mixed>|string $title
     */
    public function __construct(
        ArticleDataCore $core,
        array|string $title,
        ArticleDataBlocks $blocks,
    ) {
        $this->id = $core->id;
        $this->uuid = $core->uuid;
        $this->slug = $core->slug;
        $this->categoryId = $core->categoryId;
        $this->status = $core->status;
        $this->showOnHomepage = $core->showOnHomepage;
        $this->publishedAt = $core->publishedAt;
        $this->url = $core->url;
        $this->closedAt = $core->closedAt;
        $this->contentBlocks = $blocks->contentBlocks;
        $this->sidebarBlocks = $blocks->sidebarBlocks;
        $this->footerBlocks = $blocks->footerBlocks;

        $this->title = self::resolveTitle($title);
        $this->hydrated = $this->buildHydratedData();
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromPayload(array $payload): static
    {
        return new static(
            core: ArticleDataPayloadMapper::coreFromPayload($payload),
            title: ArticleDataPayloadMapper::titleFromPayload($payload),
            blocks: ArticleDataPayloadMapper::blocksFromPayload($payload),
        );
    }

    public static function from(mixed ...$payloads): static
    {
        if (1 === count($payloads) && is_array($payloads[0])) {
            /** @var array<string, mixed> $singlePayload */
            $singlePayload = $payloads[0];

            return static::fromPayload($singlePayload);
        }

        return parent::from(...$payloads);
    }

    public function __toString(): string
    {
        return '['.__LINE__.']['.__FILE__.']';
    }

    public function url(string $type): string
    {
        $lang = app()->getLocale();
        if ('show' === $type) {
            return '/'.$lang.'/article/'.$this->slug;
        }

        return '#';
    }

    public function __get(string $name): mixed
    {
        if (property_exists($this->hydrated, $name)) {
            return $this->hydrated->{$name};
        }

        throw new \RuntimeException(sprintf('Undefined property [%s] on ArticleData.', $name));
    }

    public function __isset(string $name): bool
    {
        return property_exists($this->hydrated, $name);
    }

    private function buildHydratedData(): ArticleDataHydrated
    {
        $categories = app(GetBloodline::class)->execute($this->categoryId);
        $closedAtDate = null !== $this->closedAt ? Carbon::parse($this->closedAt)->format('Y-m-d') : null;

        $article = ArticleDataHydrator::findByUuid($this->uuid);

        return new ArticleDataHydrated(
            categories: $categories,
            ratings: $article->getArrayRatingsWithImage(),
            closedAtDate: $closedAtDate,
            timeLeftForHumans: $article->getTimeLeftForHumans(),
            tags: $article->tags->map(
                static function (mixed $tag): string {
                    if (! is_object($tag)) {
                        return '';
                    }

                    $name = $tag->name ?? null;

                    return is_string($name) ? $name : (is_scalar($name) ? (string) $name : '');
                },
            ),
        );
    }

    /**
     * @param array<int|string, mixed>|string $title
     */
    private static function resolveTitle(array|string $title): string
    {
        $resolved = $title;
        if (is_array($title)) {
            $lang = app()->getLocale();
            $resolved = $title[$lang] ?? last($title);
        }

        return is_string($resolved)
            ? $resolved
            : (is_scalar($resolved) ? (string) $resolved : '');
    }
}
