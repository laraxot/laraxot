<?php

/**
 * ---.
 */

declare(strict_types=1);

namespace Modules\Rating\Datas;

use Modules\Rating\Enums\SupportedLocale;
use Spatie\LaravelData\Data;

/**
 * Undocumented class.
 */
class RatingData extends Data
{
    public function __construct(
        public readonly string $title = '',
        public readonly string $description = '',
        public readonly bool $disabled = false,
        public readonly int $position = 0,
        public readonly SupportedLocale $locale = SupportedLocale::IT,
        public readonly ?string $imageUrl = null,
    ) {}

    /**
     * Create from array with type casting.
     *
     * @param  array<string,mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            title: self::normalizeString($data['title'] ?? '', ''),
            description: self::normalizeString($data['description'] ?? '', ''),
            disabled: isset($data['disabled']) ? (bool) $data['disabled'] : false,
            position: isset($data['position']) && is_numeric($data['position']) ? (int) $data['position'] : 0,
            locale: SupportedLocale::fromString(self::normalizeString($data['locale'] ?? 'it', 'it')),
            imageUrl: isset($data['image_url']) ? self::normalizeNullableString($data['image_url']) : null,
        );
    }

    private static function normalizeString(mixed $value, string $default): string
    {
        if (is_string($value)) {
            return $value;
        }

        return is_scalar($value) ? (string) $value : $default;
    }

    private static function normalizeNullableString(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }
}
