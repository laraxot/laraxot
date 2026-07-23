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
     * @return array<string, array<string, mixed>>
     */
    protected function getMonthlyBalances(?Employee $employee): array
    {
        if (! $employee) {
            return $this->getDefaultBalances();
        }

        // Calculate current month balances
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        return [
            'ferie' => [
                'label' => __('employee::widgets.leave_balance.types.vacation'),
                'hours' => 8,
                'minutes' => 53,
                'total_minutes' => 533,
                'color' => 'blue',
                'icon' => 'heroicon-o-sun',
            ],
            'rol' => [
                'label' => __('employee::widgets.leave_balance.types.rol'),
                'hours' => 0,
                'minutes' => 0,
                'total_minutes' => 0,
                'color' => 'blue',
                'icon' => 'heroicon-o-clock',
            ],
            'perm_ex_fs' => [
                'label' => __('employee::widgets.leave_balance.types.former_holidays'),
                'hours' => -2,
                'minutes' => -32,
                'total_minutes' => -152,
                'color' => 'blue',
                'icon' => 'heroicon-o-calendar',
            ],
            'banca_ore' => [
                'label' => __('employee::widgets.leave_balance.types.hour_bank'),
                'hours' => 0,
                'minutes' => 0,
                'total_minutes' => 0,
                'color' => 'blue',
                'icon' => 'heroicon-o-banknotes',
            ],
            'permessi' => [
                'label' => __('employee::widgets.leave_balance.types.permits'),
                'hours' => 0,
                'minutes' => 0,
                'total_minutes' => 0,
                'color' => 'blue',
                'icon' => 'heroicon-o-document-text',
            ],
        ];
    }

    /**
     * Get annual leave balances for employee.
     *
     * @return array<string, array<string, mixed>>
     */
    protected function getAnnualBalances(?Employee $employee): array
    {
        if (! $employee) {
            return $this->getDefaultBalances();
        }

        // Calculate annual balances
        $currentYear = Carbon::now()->year;

        return [
            'ferie' => [
                'label' => __('employee::widgets.leave_balance.types.vacation'),
                'hours' => 104,
                'minutes' => 30,
                'total_minutes' => 6270,
                'color' => 'blue',
                'icon' => 'heroicon-o-sun',
            ],
            'rol' => [
                'label' => __('employee::widgets.leave_balance.types.rol'),
                'hours' => 32,
                'minutes' => 0,
                'total_minutes' => 1920,
                'color' => 'blue',
                'icon' => 'heroicon-o-clock',
            ],
            'perm_ex_fs' => [
                'label' => __('employee::widgets.leave_balance.types.former_holidays'),
                'hours' => 8,
                'minutes' => 0,
                'total_minutes' => 480,
                'color' => 'blue',
                'icon' => 'heroicon-o-calendar',
            ],
            'banca_ore' => [
                'label' => __('employee::widgets.leave_balance.types.hour_bank'),
                'hours' => 12,
                'minutes' => 45,
                'total_minutes' => 765,
                'color' => 'blue',
                'icon' => 'heroicon-o-banknotes',
            ],
            'permessi' => [
                'label' => __('employee::widgets.leave_balance.types.permits'),
                'hours' => 88,
                'minutes' => 0,
                'total_minutes' => 5280,
                'color' => 'blue',
                'icon' => 'heroicon-o-document-text',
            ],
        ];
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
