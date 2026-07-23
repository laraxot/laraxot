<?php

declare(strict_types=1);

namespace Modules\Blog\DataObjects;

use Illuminate\Support\Carbon;
use Modules\Blog\Enums\ArticleStatus;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;

class ArticleImportSchedule extends Data
{
    public function __construct(
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly ?Carbon $betEndDate = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly ?Carbon $eventStartDate = null,
        #[WithCast(DateTimeInterfaceCast::class)]
        public readonly ?Carbon $eventEndDate = null,
    ) {
    }
}

class ArticleImportIdentity extends Data
{
    public function __construct(
        public readonly string $title = '',
        public readonly string $slug = '',
        public readonly ArticleStatus $status = ArticleStatus::DRAFT,
        public readonly string $statusDisplay = '',
    ) {
    }
}

class ArticleData extends Data
{
    public function __construct(
        public readonly ArticleImportSchedule $schedule,
        public readonly ArticleImportMetrics $metrics,
        public readonly ArticleImportIdentity $identity,

        /** @var array<mixed> */
        public readonly array $category = [],

        /** @var array<mixed> */
        public readonly array $outcomes = [],
        public readonly ?string $thumbnail2x = null,
    ) {
    }

    /**
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            schedule: self::scheduleFromArray($data),
            metrics: self::metricsFromArray($data),
            identity: self::identityFromArray($data),
            category: is_array($data['category'] ?? null) ? (array) $data['category'] : [],
            outcomes: is_array($data['outcomes'] ?? null) ? (array) $data['outcomes'] : [],
            thumbnail2x: is_string($data['thumbnail_2x'] ?? null) ? $data['thumbnail_2x'] : null,
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    private static function scheduleFromArray(array $data): ArticleImportSchedule
    {
        return new ArticleImportSchedule(
            betEndDate: self::optionalDate($data, 'bet_end_date'),
            eventStartDate: self::optionalDate($data, 'event_start_date'),
            eventEndDate: self::optionalDate($data, 'event_end_date'),
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    private static function identityFromArray(array $data): ArticleImportIdentity
    {
        return new ArticleImportIdentity(
            title: self::stringValue($data, 'title'),
            slug: self::stringValue($data, 'slug'),
            status: ArticleStatus::fromString(self::stringValue($data, 'status', 'draft')),
            statusDisplay: self::stringValue($data, 'status_display'),
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    private static function metricsFromArray(array $data): ArticleImportMetrics
    {
        return new ArticleImportMetrics(
            wagerableFlag: (int) (bool) ($data['is_wagerable'] ?? false),
            scores: new ArticleImportScores(
                brierScore: self::floatValue($data, 'brier_score'),
                brierScorePlayMoney: self::floatValue($data, 'brier_score_play_money'),
                brierScoreRealMoney: self::floatValue($data, 'brier_score_real_money'),
            ),
            wagersCount: self::intValue($data, 'wagers_count'),
            wagersCountCanonical: self::intValue($data, 'wagers_count_canonical'),
            wagersCountTotal: self::intValue($data, 'wagers_count_total'),
            wagers: is_array($data['wagers'] ?? null) ? (array) $data['wagers'] : [],
            volumePlayMoney: self::floatValue($data, 'volume_play_money'),
            volumeRealMoney: self::floatValue($data, 'volume_real_money'),
        );
    }

    /**
     * @param array<string,mixed> $data
     */
    private static function optionalDate(array $data, string $key): ?Carbon
    {
        $value = $data[$key] ?? null;
        if (! isset($data[$key]) || (! is_string($value) && ! $value instanceof \DateTimeInterface)) {
            return null;
        }

        return Carbon::parse($value);
    }

    /**
     * @param array<string,mixed> $data
     */
    private static function stringValue(array $data, string $key, string $default = ''): string
    {
        return is_string($data[$key] ?? null) ? $data[$key] : $default;
    }

    /**
     * @param array<string,mixed> $data
     */
    private static function floatValue(array $data, string $key): float
    {
        return is_numeric($data[$key] ?? null) ? (float) $data[$key] : 0.0;
    }

    /**
     * @param array<string,mixed> $data
     */
    private static function intValue(array $data, string $key): int
    {
        return is_numeric($data[$key] ?? null) ? (int) $data[$key] : 0;
    }
}
