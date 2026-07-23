<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\Employee\Models\Employee;
use Modules\Employee\Models\WorkHour;
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget;

/**
 * Widget panoramica dipendenti per il dashboard Employee.
 *
 * Fornisce statistiche chiave sui dipendenti inclusi:
 * - Totale dipendenti registrati
 * - Dipendenti attivi oggi
 * - Dipendenti in ferie
 * - Nuovi assunti del mese
 */
class EmployeeOverviewWidget extends XotBaseStatsOverviewWidget
{
    /**
     * Ordine di visualizzazione del widget.
     */
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 1;

    /**
     * Restituisce le statistiche da visualizzare nel widget.
     *
     * @return array<Stat>
     */
    protected function getStats(): array
    {
        // Cache delle statistiche per 5 minuti per migliorare performance
        return cache()->remember('employee.overview.stats', 300, function (): array {
            $today = Carbon::today();
            $thisMonth = Carbon::now()->startOfMonth();

            // Totale dipendenti
            $totalEmployees = Employee::count();

            // Dipendenti attivi oggi (hanno fatto almeno una timbratura)
            // Nota: Utilizziamo la tabella work_hours per le timbrature
            $activeToday = WorkHour::whereDate('timestamp', $today)->distinct('employee_id')->count('employee_id');

            // Dipendenti in ferie (mockup - no status field in current schema)
            $onLeave = 0;

            // Nuovi dipendenti questo mese
            $newThisMonth = Employee::where('created_at', '>=', $thisMonth)->count();

            return [
                Stat::make(__('employee::widgets.overview.total_employees'), $totalEmployees)
                    ->description(__('employee::widgets.overview.total_employees_desc'))
                    ->descriptionIcon('heroicon-m-users')
                    ->color('primary')
                    ->chart($this->getEmployeeTrendChart()),
                Stat::make(__('employee::widgets.overview.active_today'), $activeToday)
                    ->description(__('employee::widgets.overview.active_today_desc'))
                    ->descriptionIcon('heroicon-m-clock')
                    ->color($activeToday > 0 ? 'success' : 'gray')
                    ->chart($this->getDailyActivityChart()),
                Stat::make(__('employee::widgets.overview.on_leave'), $onLeave)
                    ->description(__('employee::widgets.overview.on_leave_desc'))
                    ->descriptionIcon('heroicon-m-calendar')
                    ->color('success'), // Always success since no leave system implemented
                Stat::make(__('employee::widgets.overview.new_this_month'), $newThisMonth)
                    ->description(__('employee::widgets.overview.new_this_month_desc'))
                    ->descriptionIcon('heroicon-m-user-plus')
                    ->color($newThisMonth > 0 ? 'info' : 'gray')
                    ->chart($this->getMonthlyHiresChart()),
            ];
        });
    }

    /**
     * Genera dati per il grafico trend dipendenti (ultimi 7 giorni).
     *
     * @return array<int>
     */
    private function getEmployeeTrendChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $data[] = Employee::whereDate('created_at', '<=', $date)->count();
        }

        return $data;
    }

    /**
     * Genera dati per il grafico attività giornaliera (ultimi 7 giorni).
     *
     * @return array<int>
     */
    private function getDailyActivityChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $activeCount = WorkHour::whereDate('timestamp', $date)->distinct('employee_id')->count('employee_id');
            $data[] = $activeCount;
        }

        return $data;
    }

    /**
     * Genera dati per il grafico assunzioni mensili (ultimi 7 mesi).
     *
     * @return array<int>
     */
    private function getMonthlyHiresChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $data[] = Employee::whereYear('created_at', $month->year)->whereMonth('created_at', $month->month)->count();
        }

        return $data;
    }
}
