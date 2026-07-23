<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Filament\Schemas\Components\Component;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Override;

/**
 * TodoWidget - HR Task Management Widget
 *
 * Displays a list of HR tasks that need to be completed by the user.
 * Features priority-based styling and direct action links.
 */
class TodoWidget extends XotBaseSchemaWidget
{
    protected string $view = 'employee::filament.widgets.todo-widget';

    protected int|string|array $columnSpan = 1;

    protected static ?int $sort = 1;

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
     * Get todo items for the current user
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getTodoItems(): array
    {
        // Mock data for now - in production this would come from a database
        return [
            [
                'id' => 1,
                'title' => 'Una busta paga da leggere',
                'description' => 'Controlla la busta paga di dicembre 2024',
                'priority' => 'high',
                'icon' => 'heroicon-o-document-text',
                'action_url' => '#',
                'due_date' => now()->addDays(2),
            ],
            [
                'id' => 2,
                'title' => 'Aggiorna profilo dipendente',
                'description' => 'Completa le informazioni mancanti nel tuo profilo',
                'priority' => 'medium',
                'icon' => 'heroicon-o-user',
                'action_url' => '#',
                'due_date' => now()->addWeek(),
            ],
            [
                'id' => 3,
                'title' => 'Conferma ferie estive',
                'description' => 'Conferma le date delle ferie programmate per agosto',
                'priority' => 'low',
                'icon' => 'heroicon-o-calendar-days',
                'action_url' => '#',
                'due_date' => now()->addWeeks(2),
            ],
            [
                'id' => 4,
                'title' => 'Formazione obbligatoria sicurezza',
                'description' => 'Completa il corso di formazione sulla sicurezza sul lavoro',
                'priority' => 'high',
                'icon' => 'heroicon-o-shield-check',
                'action_url' => '#',
                'due_date' => now()->addDays(5),
            ],
        ];
    }

    /**
     * Get priority color class
     */
    protected function getPriorityColor(string $priority): string
    {
        return match ($priority) {
            'high' => 'text-red-600 bg-red-50 border-red-200',
            'medium' => 'text-yellow-600 bg-yellow-50 border-yellow-200',
            'low' => 'text-green-600 bg-green-50 border-green-200',
            default => 'text-gray-600 bg-gray-50 border-gray-200',
        };
    }

    /**
     * Get priority badge color
     */
    protected function getPriorityBadgeColor(string $priority): string
    {
        return match ($priority) {
            'high' => 'bg-red-100 text-red-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
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
            'todoItems' => $this->getTodoItems(),
        ];
    }
}
