<?php

declare(strict_types=1);

namespace Modules\AI\Datas;

class OpenAiPredictionMapper
{
    /**
     * @param  array<string, mixed>  $data
     */
    public static function toPredictionData(array $data): PredictionData
    {
        return PredictionData::from([
            'title' => self::toString($data['title'] ?? ''),
            'description' => self::toString($data['description'] ?? ''),
            'content' => self::toString($data['content'] ?? ''),
            'excerpt' => self::toString($data['excerpt'] ?? $data['description'] ?? ''),
            'category' => self::toString($data['category'] ?? 'Generico'),
            'tags' => self::toStringList($data['tags'] ?? []),
            'closedAt' => self::toString($data['closed_at'] ?? now()->addDays(30)->format('Y-m-d')),
            'endsAt' => self::toString($data['ends_at'] ?? now()->addDays(60)->format('Y-m-d')),
            'liquidityParameter' => is_numeric($data['liquidity_parameter'] ?? null) ? (float) $data['liquidity_parameter'] : 0.5,
            'stocksCount' => is_numeric($data['stocks_count'] ?? null) ? (int) $data['stocks_count'] : 1000,
            'isWagerable' => is_bool($data['is_wagerable'] ?? null) ? $data['is_wagerable'] : true,
            'contentBlock' => self::toNullableString($data['content_block'] ?? null),
            'sidebarBlock' => self::toNullableString($data['sidebar_block'] ?? null),
            'footerBlock' => self::toNullableString($data['footer_block'] ?? null),
        ]);
    }

    private static function toString(mixed $value, string $default = ''): string
    {
        return is_scalar($value) ? (string) $value : $default;
    }

    private static function toNullableString(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return is_scalar($value) ? (string) $value : null;
    }

    /**
     * @return list<string>
     */
    private static function toStringList(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (mixed $tag): string => is_scalar($tag) ? (string) $tag : '',
            $value
        ), static fn (string $tag): bool => $tag !== ''));
    }
}
