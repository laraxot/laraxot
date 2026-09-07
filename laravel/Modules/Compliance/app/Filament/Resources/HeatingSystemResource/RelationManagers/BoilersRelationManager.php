<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Compliance\Actions\GenerateMaintenanceInterventionAction;
use Modules\Compliance\Actions\RegisterMaintenanceControlAction;
use Modules\Compliance\Models\Boiler;
use Modules\Compliance\Models\HeatingSystem;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class BoilersRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'boilers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('heating.boilers');
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        /** @var array<int|string, string> $generatorOptions */
        $generatorOptions = collect((array) config('heating.generator_types', []))
            ->mapWithKeys(fn ($t): array => [
                (is_string($t) ? $t : '') => __('heating.generators.'.(is_string($t) ? $t : '')),
            ])->all();
        /** @var array<int|string, string> $fuelOptions */
        $fuelOptions = collect((array) config('heating.fuel_types', []))
            ->mapWithKeys(fn ($f): array => [
                (is_string($f) ? $f : '') => __('heating.fuels.'.(is_string($f) ? $f : '')),
            ])->all();

        return [
            'brand' => TextInput::make('brand')->maxLength(255),
            'model' => TextInput::make('model')->maxLength(255),
            'serial_number' => TextInput::make('serial_number')->maxLength(255),
            'generator_type' => Select::make('generator_type')->options($generatorOptions),
            'fuel_type' => Select::make('fuel_type')->options($fuelOptions),
            'power_kw' => TextInput::make('power_kw')->numeric()->suffix('kW'),
            'installation_date' => DatePicker::make('installation_date'),
            'maintenance_interval_months' => TextInput::make('maintenance_interval_months')->numeric()->minValue(1),
            'efficiency_check_interval_months' => TextInput::make('efficiency_check_interval_months')->numeric()->minValue(1),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand')->searchable(),
                Tables\Columns\TextColumn::make('model'),
                Tables\Columns\TextColumn::make('power_kw')->suffix(' kW')->toggleable(),
                Tables\Columns\TextColumn::make('next_maintenance_due_on')
                    ->date()->sortable()
                    ->color(fn (?Carbon $state): string => $this->dueColor($state)),
                Tables\Columns\TextColumn::make('next_efficiency_check_due_on')
                    ->date()->sortable()
                    ->color(fn (?Carbon $state): string => $this->dueColor($state)),
            ])
            ->headerActions([
                CreateAction::make()->label(__('heating.actions.add_boiler')),
            ])
            ->recordActions([
                $this->registerControlAction(),
                $this->generateInterventionAction(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    private function registerControlAction(): Action
    {
        return Action::make('registerControl')

            ->icon('heroicon-o-clipboard-document-check')
            ->schema([
                Select::make('control_type')

                    ->options([
                        'maintenance' => __('heating.control_types.maintenance'),
                        'efficiency_check' => __('heating.control_types.efficiency_check'),
                    ])->default('maintenance')->required(),
                DatePicker::make('performed_on')->default(now())->required(),
                Select::make('outcome')->options([
                    'conforme' => __('heating.outcomes.conforme'),
                    'non_conforme' => __('heating.outcomes.non_conforme'),
                ]),
                TextInput::make('combustion_efficiency_percent')->numeric()->suffix('%'),
                TextInput::make('co2_percent')->numeric()->suffix('%'),
                TextInput::make('o2_percent')->numeric()->suffix('%'),
                TextInput::make('co_ppm')->numeric()->suffix('ppm'),
                TextInput::make('flue_gas_temp_c')->numeric()->suffix('°C'),
                TextInput::make('smoke_index')->numeric()->minValue(0)->maxValue(9),
                TextInput::make('bollino_code'),
                Textarea::make('notes')->columnSpanFull(),
            ])
            ->action(function (array $data, Boiler $record): void {
                /** @var array<string, mixed> $data */
                /** @var HeatingSystem $system */
                $system = $this->getOwnerRecord();
                $data['performed_by_user_id'] = Auth::id();

                app(RegisterMaintenanceControlAction::class)->execute($system, $record, $data);

                Notification::make()->success()->title(__('heating.actions.control_registered'))->send();
            });
    }

    private function generateInterventionAction(): Action
    {
        return Action::make('generateIntervention')

            ->icon('heroicon-o-calendar-days')
            ->schema([
                Select::make('control_type')->options([
                    'maintenance' => __('heating.control_types.maintenance'),
                    'efficiency_check' => __('heating.control_types.efficiency_check'),
                ])->default('maintenance')->required(),
                DateTimePicker::make('scheduled_at')->default(now()->addWeek())->required(),
                Select::make('performed_by_user_id')

                    ->options(fn (): array => User::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()->preload()->required(),
            ])
            ->action(function (array $data, Boiler $record): void {
                /** @var HeatingSystem $system */
                $system = $this->getOwnerRecord();

                $scheduledAt = is_string($data['scheduled_at']) ? $data['scheduled_at'] : '';
                $performedByUserId = is_int($data['performed_by_user_id']) ? $data['performed_by_user_id'] : (is_string($data['performed_by_user_id']) ? (int) $data['performed_by_user_id'] : 0);
                $controlType = is_string($data['control_type'] ?? null) ? $data['control_type'] : 'maintenance';

                app(GenerateMaintenanceInterventionAction::class)->execute(
                    system: $system,
                    boiler: $record,
                    scheduledAt: Carbon::parse($scheduledAt),
                    performedByUserId: $performedByUserId,
                    controlType: $controlType,
                );

                Notification::make()->success()->title(__('heating.actions.intervention_generated'))->send();
            });
    }

    private function dueColor(?Carbon $state): string
    {
        if ($state === null) {
            return 'gray';
        }
        if ($state->isPast()) {
            return 'danger';
        }

        return $state->lessThanOrEqualTo(now()->addDays(30)) ? 'warning' : 'success';
    }
}
