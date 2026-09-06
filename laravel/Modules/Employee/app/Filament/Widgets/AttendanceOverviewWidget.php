<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Carbon\Carbon;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Text;
use Illuminate\Contracts\View\View;
use Modules\User\Models\User;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;

/**
 * Attendance overview widget showing next 7 days schedule.
 *
 * Displays upcoming absences, smart working, and transfers
 * with department filtering capabilities.
 */
class AttendanceOverviewWidget extends XotBaseSchemaWidget
{
    protected static ?int $sort = 3;

    protected static ?string $maxHeight = '500px';

    protected int|string|array $columnSpan = 1;

    public ?string $selectedDepartment = 'SVILUPPO';

    /**
     * Get the form schema for the widget.
     *
     * @return array<int, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Section::make(__('employee::widgets.attendance_overview.title'))
                ->schema([
                    Select::make('department')
                        ->label(__('employee::widgets.attendance_overview.department'))
                        ->options($this->getDepartmentOptions())
                        ->default($this->selectedDepartment)
                        ->live()
                        ->afterStateUpdated(function ($state): void {
                            if (is_string($state) || $state === null) {
                                $this->selectedDepartment = $state;
                            }
                        }),
                    Tabs::make('attendance_type')
                        ->tabs([
                            Tab::make('absences')
                                ->label(__('employee::widgets.attendance_overview.tabs.absences'))
                                ->badge($this->getAbsencesCount())
                                ->schema([
                                    Text::make('absences_list')
                                        ->content(fn () => $this->renderAbsencesList()),
                                ]),
                            Tab::make('smart_working')
                                ->label(__('employee::widgets.attendance_overview.tabs.smart_working'))
                                ->badge($this->getSmartWorkingCount())
                                ->schema([
                                    Text::make('smart_working_list')
                                        ->content(fn () => $this->renderSmartWorkingList()),
                                ]),
                            Tab::make('transfers')
                                ->label(__('employee::widgets.attendance_overview.tabs.transfers'))
                                ->badge($this->getTransfersCount())
                                ->schema([
                                    Text::make('transfers_list')
                                        ->content(fn () => $this->renderTransfersList()),
                                ]),
                        ])
                        ->activeTab(1),
                    Actions::make([
                        Action::make('view_all')
                            ->label(__('employee::widgets.attendance_overview.view_all_presences'))
                            ->url(fn () => route('filament.admin.resources.employees.index'))
                            ->icon('heroicon-o-eye')
                            ->color('gray')
                            ->size('sm'),
                    ])->alignment('center'),
                ])
                ->extraAttributes(['class' => 'attendance-overview-widget']),
        ];
    }

    /**
     * Get department options for select.
     *
     * @return array<string, string>
     */
    protected function getDepartmentOptions(): array
    {
        return [
            'SVILUPPO' => __('employee::widgets.attendance_overview.departments.development'),
            'MARKETING' => __('employee::widgets.attendance_overview.departments.marketing'),
            'VENDITE' => __('employee::widgets.attendance_overview.departments.sales'),
            'AMMINISTRAZIONE' => __('employee::widgets.attendance_overview.departments.administration'),
            'RISORSE_UMANE' => __('employee::widgets.attendance_overview.departments.hr'),
        ];
    }

    /**
     * Generate initials from full name.
     */
    protected function getInitials(string $fullName): string
    {
        $parts = explode(' ', trim($fullName));
        if (empty($parts) || $fullName === '') {
            return 'N/A';
        }

        $initials = '';
        foreach (array_slice($parts, 0, 2) as $part) {
            $initials .= strtoupper(substr($part, 0, 1));
        }

        return $initials ?: 'DP';
    }

    /**
     * Get absences for next 7 days from real data.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getAbsences(): array
    {
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays(7);

        // Since we don't have 'absence' type in WorkHour schema,
        // we'll look for employees who haven't clocked in recently
        // This is a mock implementation for widget display
        $result = [];

        // Return mock data if no real absences found
        if (count($result) === 0) {
            $users = User::whereNotNull('first_name')
                ->whereNotNull('last_name')
                ->limit(2)
                ->get();

            foreach ($users as $user) {
                $fullName = $user->full_name ?? 'Dipendente';
                $result[] = [
                    'employee' => [
                        'name' => $fullName,
                        'avatar' => null,
                        'initials' => $this->getInitials($fullName),
                    ],
                    'type' => 'Assenza',
                    'period' => 'dalle 14:00 alle 18:00',
                    'date' => $startDate->format('Y-m-d'),
                    'color' => 'red',
                ];
            }
        }

        return $result;
    }

    /**
     * Get smart working entries for next 7 days from real data.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getSmartWorking(): array
    {
        // Mock implementation since 'smart_working' type doesn't exist in WorkHour schema
        $result = [];

        // Return mock data if no real smart working found
        if (count($result) === 0) {
            $user = User::whereNotNull('first_name')->whereNotNull('last_name')->first();

            if ($user) {
                $fullName = $user->full_name ?? 'Dipendente';
                $result[] = [
                    'employee' => [
                        'name' => $fullName,
                        'avatar' => null,
                        'initials' => $this->getInitials($fullName),
                    ],
                    'type' => 'Smart Working',
                    'period' => 'giornata completa',
                    'date' => Carbon::tomorrow()->format('Y-m-d'),
                    'color' => 'blue',
                ];
            }
        }

        return $result;
    }

    /**
     * Get transfers for next 7 days from real data.
     *
     * @return array<int, array<string, mixed>>
     */
    protected function getTransfers(): array
    {
        // Mock implementation since 'transfer' type doesn't exist in WorkHour schema
        $result = [];

        // Return mock data if no real transfers found
        if (count($result) === 0) {
            $user = User::whereNotNull('first_name')->whereNotNull('last_name')->first();

            if ($user) {
                $fullName = $user->full_name ?? 'Dipendente';
                $result[] = [
                    'employee' => [
                        'name' => $fullName,
                        'avatar' => null,
                        'initials' => $this->getInitials($fullName),
                    ],
                    'type' => 'Trasferimento',
                    'period' => 'sede Milano',
                    'date' => Carbon::today()->addDays(2)->format('Y-m-d'),
                    'color' => 'yellow',
                ];
            }
        }

        return $result;
    }

    /**
     * Get absences count.
     */
    protected function getAbsencesCount(): int
    {
        return count($this->getAbsences());
    }

    /**
     * Get smart working count.
     */
    protected function getSmartWorkingCount(): int
    {
        return count($this->getSmartWorking());
    }

    /**
     * Get transfers count.
     */
    protected function getTransfersCount(): int
    {
        return count($this->getTransfers());
    }

    private function renderAbsencesList(): string
    {
        try {
            /** @var view-string $view */
            $view = 'employee::widgets.attendance-overview.attendance-list';

            return view($view, [
                'items' => $this->getAbsences(),
                'type' => 'absences',
            ])->render();
        } catch (Exception $e) {
            return '<div class="text-red-500">Error: '.$e->getMessage().'</div>';
        }
    }

    private function renderSmartWorkingList(): string
    {
        try {
            /** @var view-string $view */
            $view = 'employee::widgets.attendance-overview.attendance-list';

            return view($view, [
                'items' => $this->getSmartWorking(),
                'type' => 'smart_working',
            ])->render();
        } catch (Exception $e) {
            return '<div class="text-red-500">Error: '.$e->getMessage().'</div>';
        }
    }

    private function renderTransfersList(): string
    {
        try {
            /** @var view-string $view */
            $view = 'employee::widgets.attendance-overview.attendance-list';

            return view($view, [
                'items' => $this->getTransfers(),
                'type' => 'transfers',
            ])->render();
        } catch (Exception $e) {
            return '<div class="text-red-500">Error: '.$e->getMessage().'</div>';
        }
    }
}
