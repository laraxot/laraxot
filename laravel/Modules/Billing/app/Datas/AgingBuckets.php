<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

/**
 * The age bands an outstanding invoice can fall into, and the rule that picks
 * one. Thirty-day steps are the convention Italian trade suppliers and banks
 * already use, so the report reads the same as the ones an owner sees from
 * their bank.
 */
final class AgingBuckets
{
    public const string NOT_DUE = 'not_due';

    public const string DAYS_1_30 = 'days_1_30';

    public const string DAYS_31_60 = 'days_31_60';

    public const string DAYS_61_90 = 'days_61_90';

    public const string OVER_90 = 'over_90';

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        return [
            self::NOT_DUE,
            self::DAYS_1_30,
            self::DAYS_31_60,
            self::DAYS_61_90,
            self::OVER_90,
        ];
    }

    /**
     * Days late to bucket. Zero and below is not yet due — an invoice falling
     * due today has not been missed.
     */
    public static function forDaysOverdue(int $daysOverdue): string
    {
        return match (true) {
            $daysOverdue <= 0 => self::NOT_DUE,
            $daysOverdue <= 30 => self::DAYS_1_30,
            $daysOverdue <= 60 => self::DAYS_31_60,
            $daysOverdue <= 90 => self::DAYS_61_90,
            default => self::OVER_90,
        };
    }
}
