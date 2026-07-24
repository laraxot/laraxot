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
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
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

final class BoilersRelationManager extends RelationManager
{
    protected static string $relationship = 'boilers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('heating.boilers');
    }

    public function form(Schema $schema): Schema
    {
        /** @var array<int|string, string> $generatorOptions */
        $generatorOptions = collect((array) config('heating.generator_types', []))
            ->mapWithKeys(fn ($t): array => [(string) $t => __('heating.generators.'.(string) $t)])->all();
        /** @var array<int|string, string> $fuelOptions */
        $fuelOptions = collect((array) config('heating.fuel_types', []))
            ->mapWithKeys(fn ($f): array => [(string) $f => __('heating.fuels.'.(string) $f)])->all();

        return $schema->components([
            TextInput::make('brand')->label(__('heating.fields.brand'))->maxLength(255),
            TextInput::make('model')->label(__('heating.fields.model'))->maxLength(255),
            TextInput::make('serial_number')->label(__('heating.fields.serial_number'))->maxLength(255),
            Select::make('generator_type')->label(__('heating.fields.generator_type'))->options($generatorOptions),
            Select::make('fuel_type')->label(__('heating.fields.fuel_type'))->options($fuelOptions),
            TextInput::make('power_kw')->label(__('heating.fields.power_kw'))->numeric()->suffix('kW'),
            DatePicker::make('installation_date')->label(__('heating.fields.installation_date')),
            TextInput::make('maintenance_interval_months')->label(__('heating.fields.maintenance_interval_months'))->numeric()->minValue(1),
            TextInput::make('efficiency_check_interval_months')->label(__('heating.fields.efficiency_check_interval_months'))->numeric()->minValue(1),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand')->label(__('heating.fields.brand'))->searchable(),
                Tables\Columns\TextColumn::make('model')->label(__('heating.fields.model')),
                Tables\Columns\TextColumn::make('power_kw')->label(__('heating.fields.power_kw'))->suffix(' kW')->toggleable(),
                Tables\Columns\TextColumn::make('next_maintenance_due_on')
                    ->label(__('heating.fields.next_maintenance'))->date()->sortable()
                    ->color(fn (?Carbon $state): string => $this->dueColor($state)),
                Tables\Columns\TextColumn::make('next_efficiency_check_due_on')
                    ->label(__('heating.fields.next_efficiency'))->date()->sortable()
                    ->color(fn (?Carbon $state): string => $this->dueColor($state)),
            ])
            ->headerActions([
                CreateAction::make()->label(__('heating.actions.add_boiler')),
            ])
            ->actions([
                $this->registerControlAction(),
                $this->generateInterventionAction(),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    private function registerControlAction(): Action
    {
        return Action::make('registerControl')
            ->label(__('heating.actions.register_control'))
            ->icon('heroicon-o-clipboard-document-check')
            ->form([
                Select::make('control_type')
                    ->label(__('heating.fields.control_type'))
                    ->options([
                        'maintenance' => __('heating.control_types.maintenance'),
                        'efficiency_check' => __('heating.control_types.efficiency_check'),
                    ])->default('maintenance')->required(),
                DatePicker::make('performed_on')->label(__('heating.fields.performed_on'))->default(now())->required(),
                Select::make('outcome')->label(__('heating.fields.outcome'))->options([
                    'conforme' => __('heating.outcomes.conforme'),
                    'non_conforme' => __('heating.outcomes.non_conforme'),
                ]),
                TextInput::make('combustion_efficiency_percent')->label(__('heating.fields.efficiency_percent'))->numeric()->suffix('%'),
                TextInput::make('co2_percent')->label('CO₂')->numeric()->suffix('%'),
                TextInput::make('o2_percent')->label('O₂')->numeric()->suffix('%'),
                TextInput::make('co_ppm')->label('CO')->numeric()->suffix('ppm'),
                TextInput::make('flue_gas_temp_c')->label(__('heating.fields.flue_gas_temp'))->numeric()->suffix('°C'),
                TextInput::make('smoke_index')->label(__('heating.fields.smoke_index'))->numeric()->minValue(0)->maxValue(9),
                TextInput::make('bollino_code')->label(__('heating.fields.bollino_code')),
                Textarea::make('notes')->label(__('heating.fields.notes'))->columnSpanFull(),
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
            ->label(__('heating.actions.generate_intervention'))
            ->icon('heroicon-o-calendar-days')
            ->form([
                Select::make('control_type')->label(__('heating.fields.control_type'))->options([
                    'maintenance' => __('heating.control_types.maintenance'),
                    'efficiency_check' => __('heating.control_types.efficiency_check'),
                ])->default('maintenance')->required(),
                DateTimePicker::make('scheduled_at')->label(__('heating.fields.scheduled_at'))->default(now()->addWeek())->required(),
                Select::make('performed_by_user_id')
                    ->label(__('heating.fields.technician'))
                    ->options(fn (): array => User::query()->orderBy('name')->pluck('name', 'id')->all())
                    ->searchable()->preload()->required(),
            ])
            ->action(function (array $data, Boiler $record): void {
                /** @var HeatingSystem $system */
                $system = $this->getOwnerRecord();

                app(GenerateMaintenanceInterventionAction::class)->execute(
                    system: $system,
                    boiler: $record,
                    scheduledAt: Carbon::parse((string) $data['scheduled_at']),
                    performedByUserId: (int) $data['performed_by_user_id'],
                    controlType: (string) ($data['control_type'] ?? 'maintenance'),
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
