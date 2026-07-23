<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Filament\Schemas\Components\Component;
use Modules\Employee\Models\Employee;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Override;

/**
 * UpcomingScheduleWidget - 7-Day Schedule Overview Widget
 *
 * Displays upcoming events for the next 7 days including absences,
 * smart working, transfers, and other schedule items.
 */
class UpcomingScheduleWidget extends XotBaseSchemaWidget
{
    protected string $view = 'employee::filament.widgets.upcoming-schedule-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 2;

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
     * Get upcoming schedule events for the next 7 days
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getUpcomingEvents(): array
    {
        // Mock implementation since Employee->workHours relation and special types don't exist
        return [
            [
                'id' => 1,
                'employee_name' => 'Mario Rossi',
                'employee_initials' => 'MR',
                'event_type' => 'absence',
                'event_title' => 'Ferie',
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(3),
                'status' => 'approved',
                'location' => null,
                'notes' => 'Ferie programmate',
            ],
            [
                'id' => 2,
                'employee_name' => 'Sara Bianchi',
                'employee_initials' => 'SB',
                'event_type' => 'smart_working',
                'event_title' => 'Smart Working',
                'start_date' => now()->addDays(2),
                'end_date' => now()->addDays(2),
                'status' => 'approved',
                'location' => 'Casa',
                'notes' => 'Lavoro da remoto',
            ],
            [
                'id' => 3,
                'employee_name' => 'Luca Verde',
                'employee_initials' => 'LV',
                'event_type' => 'transfer',
                'event_title' => 'Trasferta',
                'start_date' => now()->addDays(4),
                'end_date' => now()->addDays(5),
                'status' => 'pending',
                'location' => 'Milano',
                'notes' => 'Meeting cliente',
            ],
        ];
    }

    /**
     * Get event type configuration
     *
     * @return array<string, string>
     */
    protected function getEventTypeConfig(string $type): array
    {
        return match ($type) {
            'absence' => [
                'icon' => 'heroicon-o-x-circle',
                'color' => 'text-red-600 bg-red-50 border-red-200',
                'badge_color' => 'bg-red-100 text-red-800',
            ],
            'smart_working' => [
                'icon' => 'heroicon-o-home',
                'color' => 'text-blue-600 bg-blue-50 border-blue-200',
                'badge_color' => 'bg-blue-100 text-blue-800',
            ],
            'transfer' => [
                'icon' => 'heroicon-o-map-pin',
                'color' => 'text-purple-600 bg-purple-50 border-purple-200',
                'badge_color' => 'bg-purple-100 text-purple-800',
            ],
            default => [
                'icon' => 'heroicon-o-calendar',
                'color' => 'text-gray-600 bg-gray-50 border-gray-200',
                'badge_color' => 'bg-gray-100 text-gray-800',
            ],
        };
    }

    /**
     * Get status badge color
     */
    protected function getStatusBadgeColor(string $status): string
    {
        return match ($status) {
            'approved' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get avatar background color based on initials
     */
    protected function getAvatarColor(string $initials): string
    {
        $colors = [
            'bg-red-500',
            'bg-blue-500',
            'bg-green-500',
            'bg-yellow-500',
            'bg-purple-500',
            'bg-pink-500',
            'bg-indigo-500',
            'bg-teal-500',
        ];

        $hash = array_sum(array_map('ord', str_split($initials)));

        return $colors[$hash % count($colors)];
    }

    /**
     * Get data for the widget view
     *
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'upcomingEvents' => $this->getUpcomingEvents(),
        ];
    }

    /**
     * Get full name from Employee model using real database fields
     */
    protected function getEmployeeFullName(Employee $employee): string
    {
        // Use full_name mutator if available
        if (! empty($employee->full_name)) {
            return $employee->full_name;
        }

        // Combine first_name + last_name
        if (! empty($employee->first_name) || ! empty($employee->last_name)) {
            return trim(($employee->first_name ?? '').' '.($employee->last_name ?? ''));
        }

        // Fallback to name field
        return $employee->name ?? ('Dipendente #'.$employee->id);
    }

    /**
     * Get initials from full name
     */
    protected function getInitialsFromName(string $fullName): string
    {
        $parts = explode(' ', trim($fullName));
        $initials = '';

        foreach ($parts as $part) {
            if (! empty($part)) {
                $initials .= strtoupper(substr($part, 0, 1));
            }
        }

        return substr($initials, 0, 2) ?: 'DP';
    }

    /**
     * Get event title based on type
     */
    protected function getEventTitle(string $type): string
    {
        return match ($type) {
            'absence' => 'Assenza',
            'smart_working' => 'Smart Working',
            'transfer' => 'Trasferta',
            'leave' => 'Ferie',
            'sick_leave' => 'Malattia',
            'permit' => 'Permesso',
            default => ucfirst($type),
        };
    }
}
