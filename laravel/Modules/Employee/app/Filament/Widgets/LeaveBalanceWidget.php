<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Carbon\Carbon;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Models\AbsenceRequest;
use Modules\Employee\Models\Employee;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Override;

/**
 * Leave balance widget showing vacation, ROL, permits and hour bank.
 *
 * Displays monthly and annual leave balances with visual progress bars.
 */
class LeaveBalanceWidget extends XotBaseSchemaWidget
{
    protected static ?int $sort = 2;

    protected static ?string $maxHeight = '400px';

    protected int|string|array $columnSpan = 1;

    /**
     * Get the form schema for the widget.
     *
     * @return array<int, Component>
     */
    #[Override]
    public function getFormSchema(): array
    {
        $userId = Auth::id();
        $employee = $userId ? Employee::find($userId) : null;
        $currentMonth = Carbon::now()->translatedFormat('F Y');

        return [
            Section::make(__('employee::widgets.leave_balance.title', ['month' => $currentMonth]))
                ->schema([
                    Tabs::make('leave_period')
                        ->tabs([
                            Tab::make('monthly')
                                ->label(__('employee::widgets.leave_balance.monthly'))
                                ->schema([
                                    Placeholder::make('monthly_balances')->content(fn () => view('employee::widgets.leave-balance.balance-display', [
                                        'balances' => $this->getMonthlyBalances($employee),
                                        'type' => 'monthly',
                                    ])),
                                ]),
                            Tab::make('annual')
                                ->label(__('employee::widgets.leave_balance.annual'))
                                ->schema([
                                    Placeholder::make('annual_balances')->content(fn () => view('employee::widgets.leave-balance.balance-display', [
                                        'balances' => $this->getAnnualBalances($employee),
                                        'type' => 'annual',
                                    ])),
                                ]),
                        ])
                        ->activeTab(1),
                ])
                ->extraAttributes(['class' => 'leave-balance-widget']),
        ];
    }

    /**
     * Get monthly leave balances for employee.
     *
     * Computed from real `AbsenceRequest` records (approved only), replacing
     * the previous hardcoded balances.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getMonthlyBalances(?Employee $employee): array
    {
        if (! $employee) {
            return $this->getDefaultBalances();
        }

        return $this->buildBalances(
            $employee,
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        );
    }

    /**
     * Get annual leave balances for employee.
     *
     * Computed from real `AbsenceRequest` records (approved only), replacing
     * the previous hardcoded balances.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getAnnualBalances(?Employee $employee): array
    {
        if (! $employee) {
            return $this->getDefaultBalances();
        }

        return $this->buildBalances(
            $employee,
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        );
    }

    /**
     * Build the leave balances array by summing approved AbsenceRequest hours per type
     * within the given period.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function buildBalances(Employee $employee, Carbon $from, Carbon $to): array
    {
        $usedMinutesByType = AbsenceRequest::query()
            ->where('user_id', $employee->getKey())
            ->where('status', AbsenceRequest::STATUS_APPROVED)
            ->whereBetween('starts_at', [$from, $to])
            ->get()
            ->groupBy('type')
            ->map(fn ($requests) => (int) $requests->sum(
                fn (AbsenceRequest $request): int => (int) $request->ends_at->diffInMinutes($request->starts_at)
            ));

        $definitions = [
            'ferie' => ['type' => AbsenceRequest::TYPE_VACATION, 'label_key' => 'vacation', 'icon' => 'heroicon-o-sun'],
            'permessi' => ['type' => AbsenceRequest::TYPE_LEAVE, 'label_key' => 'permits', 'icon' => 'heroicon-o-document-text'],
            'malattia' => ['type' => AbsenceRequest::TYPE_SICK, 'label_key' => 'former_holidays', 'icon' => 'heroicon-o-heart'],
            'infortunio' => ['type' => AbsenceRequest::TYPE_INJURY, 'label_key' => 'hour_bank', 'icon' => 'heroicon-o-banknotes'],
        ];

        $balances = [];

        foreach ($definitions as $key => $definition) {
            $totalMinutes = (int) ($usedMinutesByType[$definition['type']] ?? 0);

            $balances[$key] = [
                'label' => __("employee::widgets.leave_balance.types.{$definition['label_key']}"),
                'hours' => intdiv($totalMinutes, 60),
                'minutes' => $totalMinutes % 60,
                'total_minutes' => $totalMinutes,
                'color' => 'blue',
                'icon' => $definition['icon'],
            ];
        }

        return $balances;
    }

    /**
     * Get default balances when no employee found.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getDefaultBalances(): array
    {
        return [
            'ferie' => [
                'label' => __('employee::widgets.leave_balance.types.vacation'),
                'hours' => 0,
                'minutes' => 0,
                'total_minutes' => 0,
                'color' => 'gray',
                'icon' => 'heroicon-o-sun',
            ],
            'rol' => [
                'label' => __('employee::widgets.leave_balance.types.rol'),
                'hours' => 0,
                'minutes' => 0,
                'total_minutes' => 0,
                'color' => 'gray',
                'icon' => 'heroicon-o-clock',
            ],
            'perm_ex_fs' => [
                'label' => __('employee::widgets.leave_balance.types.former_holidays'),
                'hours' => 0,
                'minutes' => 0,
                'total_minutes' => 0,
                'color' => 'gray',
                'icon' => 'heroicon-o-calendar',
            ],
            'banca_ore' => [
                'label' => __('employee::widgets.leave_balance.types.hour_bank'),
                'hours' => 0,
                'minutes' => 0,
                'total_minutes' => 0,
                'color' => 'gray',
                'icon' => 'heroicon-o-banknotes',
            ],
            'permessi' => [
                'label' => __('employee::widgets.leave_balance.types.permits'),
                'hours' => 0,
                'minutes' => 0,
                'total_minutes' => 0,
                'color' => 'gray',
                'icon' => 'heroicon-o-document-text',
            ],
        ];
    }
}
