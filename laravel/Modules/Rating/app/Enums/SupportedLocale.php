<?php

declare(strict_types=1);

namespace Modules\Rating\Enums;

use Modules\Xot\Traits\EnumTrait;

enum SupportedLocale: string
{
    use EnumTrait;

    case IT = 'it';
    case EN = 'en';

    /**
     * Create from string value.
     */
    public static function fromString(string $value): self
    {
        return match ($value) {
            'it' => self::IT,
            'en' => self::EN,
            default => self::IT,
        };
    }

    /** @return array<int|string, string> */
    public static function toArray(): array
    {
        $cases = self::cases();
        $result = [];
        foreach ($cases as $item) {
            $result[(string) $item->value] = (string) $item->getLabel();
        }

        return $result;
    }
}
