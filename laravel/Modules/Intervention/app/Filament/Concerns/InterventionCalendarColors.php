<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Concerns;

trait InterventionCalendarColors
{
    /**
     * @return array<int, string>
     */
    protected static function operatorColors(): array
    {
        return [
            '#dc2626',
            '#2563eb',
            '#16a34a',
            '#d97706',
            '#7c3aed',
            '#ea580c',
            '#0f766e',
            '#db2777',
            '#1d4ed8',
            '#166534',
        ];
    }

    protected static function operatorColorFor(int $operatorId): string
    {
        $colors = self::operatorColors();
        $colorIndex = $operatorId % count($colors);

        return $colors[$colorIndex];
    }

    protected static function eventBorderColor(?string $outcomeCode): string
    {
        return match ($outcomeCode) {
            'completed' => '#15803d',
            'suspended' => '#c2410c',
            default => '#475569',
        };
    }

    protected static function eventTextColor(string $backgroundColor): string
    {
        $normalized = ltrim($backgroundColor, '#');

        if (strlen($normalized) !== 6) {
            return '#111827';
        }

        $red = hexdec(substr($normalized, 0, 2));
        $green = hexdec(substr($normalized, 2, 2));
        $blue = hexdec(substr($normalized, 4, 2));
        $luminance = (0.299 * $red) + (0.587 * $green) + (0.114 * $blue);

        return $luminance < 140 ? '#ffffff' : '#111827';
    }
}
