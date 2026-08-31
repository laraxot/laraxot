<?php

declare(strict_types=1);

namespace Modules\Wts\Support;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Throwable;

final class WtsLegacyPayload
{
    /**
     * @param  array<string, mixed>|null  $payload
     * @param  array<int, string>  $keys
     */
    public static function firstString(?array $payload, array $keys): ?string
    {
        if (! is_array($payload)) {
            return null;
        }

        foreach ($keys as $key) {
            $value = data_get($payload, $key);
            if (is_scalar($value) && trim((string) $value) !== '') {
                return trim((string) $value);
            }
        }

        return null;
    }

    /**
     * @param  array<string, mixed>|null  $payload
     */
    public static function movementDate(?array $payload, CarbonInterface|string|null $fallback = null): ?CarbonImmutable
    {
        $dateKeys = config('wts.legacy_movement_date_keys', []);

        if (is_array($payload) && is_array($dateKeys)) {
            foreach ($dateKeys as $key) {
                if (! is_string($key)) {
                    continue;
                }

                $resolved = self::parseDateValue(data_get($payload, $key));
                if ($resolved instanceof CarbonImmutable) {
                    return $resolved;
                }
            }
        }

        return self::parseDateValue($fallback);
    }

    /**
     * @param  array<string, mixed>|null  $payload
     */
    public static function matchesDate(?array $payload, CarbonInterface|string|null $fallback, ?string $expectedDate): bool
    {
        $normalizedExpectedDate = trim((string) $expectedDate);
        if ($normalizedExpectedDate === '') {
            return true;
        }

        $movementDate = self::movementDate($payload, $fallback);
        if (! $movementDate instanceof CarbonImmutable) {
            return false;
        }

        return $movementDate->toDateString() === $normalizedExpectedDate;
    }

    private static function parseDateValue(mixed $value): ?CarbonImmutable
    {
        if ($value instanceof CarbonImmutable) {
            return $value;
        }

        if ($value instanceof CarbonInterface) {
            return CarbonImmutable::instance($value);
        }

        if (! is_scalar($value)) {
            return null;
        }

        $normalizedValue = trim((string) $value);
        if ($normalizedValue === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($normalizedValue);
        } catch (Throwable) {
            return null;
        }
    }
}
