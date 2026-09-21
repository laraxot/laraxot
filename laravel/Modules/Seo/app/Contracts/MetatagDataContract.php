<?php

declare(strict_types=1);

namespace Modules\Seo\Contracts;

use DateTimeInterface;
use Modules\Seo\Datas\MetatagData;

/**
 * Contratto read-only per i metadati SEO (title, OG, canonical, …).
 *
 * @see MetatagData Implementazione Spatie Data + Livewire Wireable
 */
interface MetatagDataContract
{
    public function getTitle(): string;

    public function getDescription(): string;

    public function getKeywords(): string;

    /**
     * @return array<string, string>
     */
    public function getColors(): array;

    public function getRobots(): string;

    public function getCanonical(): ?string;

    public function getImage(): ?string;

    public function getLocale(): string;

    public function getType(): string;

    public function getSiteName(): string;

    public function getUrl(): ?string;

    public function getAuthor(): ?string;

    public function getPublishedTime(): ?DateTimeInterface;

    public function getModifiedTime(): ?DateTimeInterface;

    /**
     * Get a metadata value by key.
     *
     * @param  string  $key  The metadata key
     * @param  array<string, mixed>|string|int|float|bool|null  $default  Default value if key doesn't exist
     * @return array<string, mixed>|string|int|float|bool|null The metadata value or default
     */
    public function get(string $key, array|string|int|float|bool|null $default = null): array|string|int|float|bool|null;

    public function has(string $key): bool;

    /**
     * Convert the object to its array representation.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
