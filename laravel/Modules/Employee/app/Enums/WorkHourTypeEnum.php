<?php

declare(strict_types=1);

namespace Modules\Employee\Enums;

enum WorkHourTypeEnum: string
{
    case CLOCK_IN = 'clock_in';
    case CLOCK_OUT = 'clock_out';
    case BREAK_START = 'break_start';
    case BREAK_END = 'break_end';

    /**
     * Get all enum values as array.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get enum label for display.
     */
    public function getLabel(): string
    {
        return match ($this) {
            self::CLOCK_IN => 'Clock In',
            self::CLOCK_OUT => 'Clock Out',
            self::BREAK_START => 'Break Start',
            self::BREAK_END => 'Break End',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::CLOCK_IN => 'success',
            self::CLOCK_OUT => 'danger',
            self::BREAK_START => 'warning',
            self::BREAK_END => 'info',
        };
    }
}
