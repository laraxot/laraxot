<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Override;
use Webmozart\Assert\Assert;

/**
 * TimeOffBalanceWidget - Leave Balance Display Widget
 *
 * Displays monthly/annual leave balances including vacation days,
 * ROL, permits, overtime bank, and other time-off categories.
 */
class TimeOffBalanceWidget extends XotBaseSchemaWidget
{
    protected string $view = 'employee::filament.widgets.time-off-balance-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 3;

    /**
     * Get the form schema for the widget.
     *
     * @return array<int|string, Component>
     */
    #[Override]
    public function getFormSchema(): array
    {
        return [];
    }

    /**
     * Get time-off balance data for the current user
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getTimeOffBalances(): array
    {
        // Mock data for now - in production this would come from a database
        return [
            [
                'id' => 1,
                'type' => 'vacation',
                'label' => 'Ferie',
                'current_balance' => 8.88, // 8h 53m
                'total_allowance' => 22.0,
                'used' => 13.12,
                'unit' => 'hours',
                'color' => 'blue',
                'icon' => 'heroicon-o-sun',
            ],
            [
                'id' => 2,
                'type' => 'rol',
                'label' => 'ROL',
                'current_balance' => 0.0,
                'total_allowance' => 8.0,
                'used' => 8.0,
                'unit' => 'hours',
                'color' => 'green',
                'icon' => 'heroicon-o-clock',
            ],
            [
                'id' => 3,
                'type' => 'permits_ex_fs',
                'label' => 'Perm. ex-fs',
                'current_balance' => -2.53, // -2h 32m
                'total_allowance' => 4.0,
                'used' => 6.53,
                'unit' => 'hours',
                'color' => 'red',
                'icon' => 'heroicon-o-document-text',
            ],
            [
                'id' => 4,
                'type' => 'overtime_bank',
                'label' => 'Banca ore',
                'current_balance' => 12.25, // 12h 15m
                'total_allowance' => null, // No limit
                'used' => 0.0,
                'unit' => 'hours',
                'color' => 'purple',
                'icon' => 'heroicon-o-banknotes',
            ],
            [
                'id' => 5,
                'type' => 'permits',
                'label' => 'Permessi',
                'current_balance' => 3.75, // 3h 45m
                'total_allowance' => 8.0,
                'used' => 4.25,
                'unit' => 'hours',
                'color' => 'yellow',
                'icon' => 'heroicon-o-hand-raised',
            ],
        ];
    }

    /**
     * Format hours to hours and minutes display
     */
    protected function formatHoursMinutes(float $hours): string
    {
        if (abs($hours) < 0.01) { // Use tolerance for float comparison
            Assert::true(
                abs($hours) < 0.01,
                __FILE__.':'.__LINE__.' - '.class_basename(__CLASS__).' - Hours value is effectively zero (within tolerance of 0.01)'
            );

            return '0';
        }

        $isNegative = $hours < 0;
        $absHours = abs($hours);
        $wholeHours = floor($absHours);
        $minutes = round(($absHours - $wholeHours) * 60);

        if ($minutes >= 60) {
            Assert::greaterThanEq(
                $minutes,
                60,
                __FILE__.':'.__LINE__.' - '.class_basename(__CLASS__).' - Minutes should be 60 or more to warrant hour increment'
            );
            $wholeHours++;
            $minutes = 0;
        }

        $formatted = '';
        if ($wholeHours > 0) {
            $formatted .= $wholeHours.'h';
        }
        if ($minutes > 0) {
            $formatted .= ($wholeHours > 0 ? ' ' : '').$minutes.'m';
        }

        return ($isNegative ? '-' : '').$formatted;
    }

    /**
     * Get progress percentage for balance
     */
    protected function getProgressPercentage(float $used, ?float $total): float
    {
        if (! $total || $total <= 0) {
            return 0;
        }

        return min(100, max(0, ($used / $total) * 100));
    }

    /**
     * Get color classes for balance type
     *
     * @return array<string, string>
     */
    protected function getColorClasses(string $color, float $balance): array
    {
        $isNegative = $balance < 0;

        if ($isNegative) {
            return [
                'bg' => 'bg-red-50',
                'border' => 'border-red-200',
                'text' => 'text-red-900',
                'progress' => 'bg-red-500',
                'progress_bg' => 'bg-red-100',
            ];
        }

        return match ($color) {
            'blue' => [
                'bg' => 'bg-blue-50',
                'border' => 'border-blue-200',
                'text' => 'text-blue-900',
                'progress' => 'bg-blue-500',
                'progress_bg' => 'bg-blue-100',
            ],
            'green' => [
                'bg' => 'bg-green-50',
                'border' => 'border-green-200',
                'text' => 'text-green-900',
                'progress' => 'bg-green-500',
                'progress_bg' => 'bg-green-100',
            ],
            'red' => [
                'bg' => 'bg-red-50',
                'border' => 'border-red-200',
                'text' => 'text-red-900',
                'progress' => 'bg-red-500',
                'progress_bg' => 'bg-red-100',
            ],
            'purple' => [
                'bg' => 'bg-purple-50',
                'border' => 'border-purple-200',
                'text' => 'text-purple-900',
                'progress' => 'bg-purple-500',
                'progress_bg' => 'bg-purple-100',
            ],
            'yellow' => [
                'bg' => 'bg-yellow-50',
                'border' => 'border-yellow-200',
                'text' => 'text-yellow-900',
                'progress' => 'bg-yellow-500',
                'progress_bg' => 'bg-yellow-100',
            ],
            default => [
                'bg' => 'bg-gray-50',
                'border' => 'border-gray-200',
                'text' => 'text-gray-900',
                'progress' => 'bg-gray-500',
                'progress_bg' => 'bg-gray-100',
            ],
        };
    }

    /**
     * Get data for the widget view
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'timeOffBalances' => $this->getTimeOffBalances(),
            'currentMonth' => now()->format('F Y'),
        ];
    }
}
