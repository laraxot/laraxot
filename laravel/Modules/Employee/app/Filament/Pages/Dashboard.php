<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Pages;

use Modules\Employee\Filament\Widgets;
use Modules\Employee\Filament\Widgets\TimeClockWidget;
use Modules\Xot\Filament\Pages\XotBaseDashboard;

/**
 * Dashboard per il modulo Employee.
 *
 *
 * Estende XotBaseDashboard che gestisce automaticamente:
 * - Navigazione (icon, title, label, sort)
 * - Filtri del dashboard
 * - Struttura base del dashboard
 *
 *
 * REGOLA CRITICA: NON ridefinire proprietà di navigazione
 * che sono già gestite centralmente da XotBaseDashboard.
 */
class Dashboard extends XotBaseDashboard
{
    // ❌ NON ridefinire queste proprietà (gestite da XotBaseDashboard):
    // protected static ?string $navigationIcon
    // protected static ?string $title
    // protected static ?string $navigationLabel
    // protected static ?int $navigationSort

    // ✅ XotBaseDashboard auto-configura tutto basandosi sul modulo

    // protected static string $view = 'employee::filament.pages.dashboard';

    /**
     * Widget da visualizzare nella pagina con configurazione columnSpan personalizzata.
     * TimeClockWidget a tutta larghezza, altri widget in griglia 3x2 (4 colonne ciascuno).
     *
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            TimeClockWidget::class,
            // Widgets\TodoWidget::class,
            // Widgets\UpcomingScheduleWidget::class,
            // Widgets\PendingRequestsWidget::class,
            // Widgets\TimeOffBalanceWidget::class,
            // Widgets\TodayPresenceWidget::class,
        ];
    }

    /**
     * Configura il numero di colonne per i widget (3 widget per riga).
     *
     * @return int|array<string, int>
     */
    protected function getWidgetsColumns(): int|array
    {
        return 3;
    }
}
