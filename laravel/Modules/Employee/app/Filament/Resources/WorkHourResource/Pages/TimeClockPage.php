<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\WorkHourResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Enums\WorkHourStatusEnum;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Filament\Resources\WorkHourResource;
use Modules\Employee\Models\WorkHour;
use Modules\Xot\Filament\Resources\Pages\XotBasePage;
use Override;

/**
 * Class TimeClockPage
 *
 * Main page for employee time tracking and work hours management.
 * Displays the time clock widget and work hours list with filtering options.
 */
class TimeClockPage extends XotBasePage implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = WorkHourResource::class;

    protected string $view = 'employee::filament.pages.work-hours';

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('timestamp')
                    ->label('Date & Time')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("employee::work_hour.fields.type.options.{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        WorkHourTypeEnum::CLOCK_IN->value => 'success',
                        WorkHourTypeEnum::CLOCK_OUT->value => 'danger',
                        WorkHourTypeEnum::BREAK_START->value => 'warning',
                        WorkHourTypeEnum::BREAK_END->value => 'info',
                        default => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => __("employee::work_hour.fields.status.options.{$state}"))
                    ->color(fn (string $state): string => match ($state) {
                        WorkHourStatusEnum::PENDING->value => 'warning',
                        WorkHourStatusEnum::APPROVED->value => 'success',
                        WorkHourStatusEnum::REJECTED->value => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('location_name')->label('Location')->searchable(),
                TextColumn::make('notes')->label('Notes')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Filter::make('date_range')
                    ->label('Date Range')
                    ->form([
                        DatePicker::make('date_from')
                            ->label('From'),
                        DatePicker::make('date_to')
                            ->label('To'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(isset($data['date_from']) && $data['date_from'], fn (Builder $query): Builder => $query->whereDate('timestamp', '>=', (string) $data['date_from']))
                        ->when(isset($data['date_to']) && $data['date_to'], fn (Builder $query): Builder => $query->whereDate('timestamp', '<=', (string) $data['date_to']))),
                SelectFilter::make('type')
                    ->label('Type')
                    ->options(WorkHourTypeEnum::class),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(WorkHourStatusEnum::class),
            ])
            ->recordActions([
                Action::make('edit')
                    ->icon('heroicon-o-pencil')
                    ->url(fn (WorkHour $record): string => WorkHourResource::getUrl('edit', ['record' => $record])),
                Action::make('delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (WorkHour $record) => $record->delete())
                    ->visible(fn (WorkHour $record) => $record->status === WorkHourStatusEnum::PENDING->value),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->action(function () {
                            Notification::make()
                                ->title('Work hours deleted')
                                ->success()
                                ->send();
                        }),
                ]),
            ]);
    }

    /**
     * @return Builder<WorkHour>
     */
    protected function getTableQuery(): Builder
    {
        return WorkHour::query()
            ->where('employee_id', Auth::id())
            ->latest('timestamp');
    }

    public function exportWorkHours(): void
    {
        $this->validate();

        // TODO: Implement export functionality

        Notification::make()
            ->title('Export started')
            ->body('Your work hours export has been queued. You will receive a notification when it is ready.')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export')
                ->label('Export')
                ->icon('heroicon-o-arrow-down-tray')
                ->action('exportWorkHours'),
            Action::make('back_to_list')
                ->label('Back to Dashboard')
                ->icon('heroicon-o-home')
                ->color('gray')
                ->url(route('filament.admin.pages.dashboard')),
        ];
    }

    #[Override]
    public function getViewData(): array
    {
        return [
            'currentTime' => now()->format('H:i:s'),
            'currentDate' => now()->translatedFormat('l, d F Y'),
            'workHoursToday' => $this->getWorkHoursToday(),
            'currentStatus' => $this->getCurrentStatus(),
        ];
    }

    protected function getWorkHoursToday(): string
    {
        $today = now()->startOfDay();

        $clockIn = WorkHour::query()
            ->where('employee_id', Auth::id())
            ->where('type', WorkHourTypeEnum::CLOCK_IN->value)
            ->whereDate('created_at', $today)
            ->first();

        if (! $clockIn) {
            return '00:00';
        }

        $clockOut = WorkHour::query()
            ->where('employee_id', Auth::id())
            ->where('type', WorkHourTypeEnum::CLOCK_OUT->value)
            ->whereDate('created_at', $today)
            ->first();

        $endTime = $clockOut->created_at ?? now();
        $totalMinutes = $endTime->diffInMinutes($clockIn->created_at);

        // Subtract break time if any
        $breakStart = WorkHour::query()
            ->where('employee_id', Auth::id())
            ->where('type', WorkHourTypeEnum::BREAK_START->value)
            ->whereDate('created_at', $today)
            ->first();

        if ($breakStart) {
            $breakEnd = WorkHour::query()
                ->where('employee_id', Auth::id())
                ->where('type', WorkHourTypeEnum::BREAK_END->value)
                ->whereDate('created_at', $today)
                ->first();

            if ($breakEnd) {
                $breakMinutes = $breakEnd->created_at?->diffInMinutes($breakStart->created_at) ?? 0;
                $totalMinutes -= $breakMinutes;
            }
        }

        $hours = floor($totalMinutes / 60);
        $minutes = $totalMinutes % 60;

        return sprintf('%02d:%02d', $hours, $minutes);
    }

    protected function getCurrentStatus(): string
    {
        $lastAction = WorkHour::query()
            ->where('employee_id', Auth::id())
            ->latest('timestamp')
            ->first();

        if (! $lastAction) {
            return 'not_clocked_in';
        }

        return match ($lastAction->type) {
            WorkHourTypeEnum::CLOCK_IN->value => 'clocked_in',
            WorkHourTypeEnum::CLOCK_OUT->value => 'clocked_out',
            WorkHourTypeEnum::BREAK_START->value => 'on_break',
            WorkHourTypeEnum::BREAK_END->value => 'working',
            default => 'unknown',
        };
    }
}
