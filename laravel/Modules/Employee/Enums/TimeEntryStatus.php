<?php

declare(strict_types=1);

namespace Modules\Employee\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Xot\Traits\EnumTrait;

/**
 * Enum per stati TimeEntry
 */
enum TimeEntryStatus: string implements HasColor, HasIcon, HasLabel
{
    use EnumTrait;

    case PENDING = 'pending';
    case APPROVED = 'approved';
    case AUTO_APPROVED = 'auto_approved';
    case REJECTED = 'rejected';

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isApproved(): bool
    {
        return in_array($this, [self::APPROVED, self::AUTO_APPROVED]);
    }

    public function isRejected(): bool
    {
        return $this === self::REJECTED;
    }

    public function isFinal(): bool
    {
        return $this !== self::PENDING;
    }
}