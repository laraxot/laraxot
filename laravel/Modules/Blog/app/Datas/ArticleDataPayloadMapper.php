<?php

declare(strict_types=1);

namespace Modules\Blog\Datas;

final class ArticleDataPayloadMapper
{
    /**
     * @param array<string, mixed> $payload
     */
    public static function coreFromPayload(array $payload): ArticleDataCore
    {
        return new ArticleDataCore(
            id: (string) ($payload['id'] ?? ''),
            uuid: (string) ($payload['uuid'] ?? ''),
            slug: (string) ($payload['slug'] ?? ''),
            categoryId: self::nullableInt($payload, 'categoryId', 'category_id'),
            status: self::nullableString($payload, 'status'),
            showOnHomepage: (bool) ($payload['showOnHomepage'] ?? $payload['show_on_homepage'] ?? false),
            publishedAt: self::nullableString($payload, 'publishedAt', 'published_at'),
            url: self::nullableString($payload, 'url'),
            closedAt: self::nullableString($payload, 'closedAt', 'closed_at'),
        );
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function blocksFromPayload(array $payload): ArticleDataBlocks
    {
        return new ArticleDataBlocks(
            contentBlocks: self::nullableArray($payload, 'contentBlocks', 'content_blocks'),
            sidebarBlocks: self::nullableArray($payload, 'sidebarBlocks', 'sidebar_blocks'),
            footerBlocks: self::nullableArray($payload, 'footerBlocks', 'footer_blocks'),
        );
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<int|string, mixed>|string
     */
    public static function titleFromPayload(array $payload): array|string
    {
        $title = $payload['title'] ?? '';

        return is_array($title) || is_string($title) ? $title : '';
    }

    /**
     * @param array<string, mixed> $payload
     */
    private static function nullableInt(array $payload, string $primaryKey, string $fallbackKey): ?int
    {
        if (isset($payload[$primaryKey])) {
            return (int) $payload[$primaryKey];
        }

        if (isset($payload[$fallbackKey])) {
            return (int) $payload[$fallbackKey];
        }

        return null;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private static function nullableString(array $payload, string $primaryKey, ?string $fallbackKey = null): ?string
    {
        if (is_string($payload[$primaryKey] ?? null)) {
            return $payload[$primaryKey];
        }

        if (null !== $fallbackKey && is_string($payload[$fallbackKey] ?? null)) {
            return $payload[$fallbackKey];
        }

        return null;
    }

    /**
     * @param array<string, mixed> $payload
     *
     * @return array<int|string, mixed>|null
     */
    private static function nullableArray(array $payload, string $primaryKey, string $fallbackKey): ?array
    {
        if (is_array($payload[$primaryKey] ?? null)) {
            return $payload[$primaryKey];
        }

        if (is_array($payload[$fallbackKey] ?? null)) {
            return $payload[$fallbackKey];
        }

        return null;
    }
}
