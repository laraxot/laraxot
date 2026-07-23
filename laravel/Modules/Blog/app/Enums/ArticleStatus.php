<?php

declare(strict_types=1);

namespace Modules\Blog\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Enum for article statuses.
 *
 * Uses EnumTrait for getLabel(), getColor().
 * Configure values in: Modules/Blog/lang/{locale}/enums.php
 */
enum ArticleStatus: string implements HasColor, HasLabel
{
    use EnumTrait;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case PENDING = 'pending';

    public static function fromString(string $value): self
    {
        return match ($value) {
            'draft' => self::DRAFT,
            'published' => self::PUBLISHED,
            'archived' => self::ARCHIVED,
            'pending' => self::PENDING,
            default => throw new \InvalidArgumentException("Invalid status: {$value}"),
        };
    }
}
