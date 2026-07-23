<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Override;

/**
 * PendingRequestsWidget - Employee Request Status Widget
 *
 * Displays pending approval requests for the current employee
 * with status tracking and illustrations for empty states.
 */
class PendingRequestsWidget extends XotBaseSchemaWidget
{
    protected string $view = 'employee::filament.widgets.pending-requests-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 5;

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
     * Get pending requests for the current user
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getPendingRequests(): array
    {
        // Mock data for demonstration - in production this would come from a database
        return [
            [
                'id' => 1,
                'type' => 'vacation',
                'title' => 'Ferie Natale',
                'description' => 'Ferie dal 23 dicembre al 2 gennaio',
                'submitted_date' => now()->subDays(2),
                'status' => 'pending',
                'approver' => 'Mario Rossi',
                'priority' => 'normal',
                'icon' => 'heroicon-o-sun',
            ],
            [
                'id' => 2,
                'type' => 'permit',
                'title' => 'Permesso Medico',
                'description' => 'Visita cardiologica - 3 ore',
                'submitted_date' => now()->subDay(),
                'status' => 'pending',
                'approver' => 'Sara Bianchi',
                'priority' => 'high',
                'icon' => 'heroicon-o-heart',
            ],
            [
                'id' => 3,
                'type' => 'smart_working',
                'title' => 'Smart Working',
                'description' => 'Lavoro da casa - venerdì',
                'submitted_date' => now()->subHours(6),
                'status' => 'pending',
                'approver' => 'Luca Verdi',
                'priority' => 'normal',
                'icon' => 'heroicon-o-home',
            ],
        ];
    }

    /**
     * Get request type configuration
     *
     * @return array<string, string>
     */
    protected function getRequestTypeConfig(string $type): array
    {
        return match ($type) {
            'vacation' => [
                'icon' => 'heroicon-o-sun',
                'color' => 'text-orange-600',
                'bg' => 'bg-orange-50',
                'border' => 'border-orange-200',
            ],
            'sick' => [
                'icon' => 'heroicon-o-heart',
                'color' => 'text-red-600',
                'bg' => 'bg-red-50',
                'border' => 'border-red-200',
            ],
            'permit' => [
                'icon' => 'heroicon-o-document-text',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-50',
                'border' => 'border-blue-200',
            ],
            'smart_working' => [
                'icon' => 'heroicon-o-home',
                'color' => 'text-green-600',
                'bg' => 'bg-green-50',
                'border' => 'border-green-200',
            ],
            'transfer' => [
                'icon' => 'heroicon-o-map-pin',
                'color' => 'text-purple-600',
                'bg' => 'bg-purple-50',
                'border' => 'border-purple-200',
            ],
            default => [
                'icon' => 'heroicon-o-document',
                'color' => 'text-gray-600',
                'bg' => 'bg-gray-50',
                'border' => 'border-gray-200',
            ],
        };
    }

    /**
     * Get status badge color
     */
    protected function getStatusBadgeColor(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'under_review' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get priority badge color
     */
    protected function getPriorityBadgeColor(string $priority): string
    {
        return match ($priority) {
            'high' => 'bg-red-100 text-red-800',
            'normal' => 'bg-blue-100 text-blue-800',
            'low' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
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
            'pendingRequests' => $this->getPendingRequests(),
        ];
    }
}
