<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Resources\Resource;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Resources\PlatformSettingResource\Pages\EditPlatformSetting;
use Modules\Platform\Filament\Resources\PlatformSettingResource\Pages\ListPlatformSettings;
use Modules\Platform\Models\PlatformSetting;

final class PlatformSettingResource extends Resource
{
    protected static ?string $model = PlatformSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('platform.settings');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit($record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView($record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationLabel(): string
    {
        return __('platform.settings');
    }

    public static function getModelLabel(): string
    {
        return __('platform.settings');
    }

    public static function getPluralModelLabel(): string
    {
        return __('platform.settings');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->label(__('platform.fields.key'))
                    ->required()
                    ->disabled(fn (?Model $record): bool => $record !== null)
                    ->dehydrated()
                    ->live()
                    ->visible(fn (?Model $record): bool => $record === null)
                    ->maxLength(190),

                Placeholder::make('key_human')
                    ->label(__('platform.fields.setting'))
                    ->content(function (callable $get): string {
                        $key = (string) $get('key');
                        return self::humanLabelForKey($key);
                    })
                    ->visible(fn (?Model $record): bool => $record !== null),

                Select::make('inventory_trace_mode')
                    ->label(__('platform.inventory.trace_mode'))
                    ->options([
                        'aggregate' => __('platform.inventory.trace_mode_options.aggregate'),
                        'unit_serial' => __('platform.inventory.trace_mode_options.unit_serial'),
                        'both' => __('platform.inventory.trace_mode_options.both'),
                    ])
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.trace_mode')
                    ->required(fn (callable $get): bool => (string) $get('key') === 'inventory.trace_mode'),

                Toggle::make('inventory_allow_link_work_orders')
                    ->label(__('platform.inventory.allow_link_work_orders'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.allow_link_work_orders'),

                Toggle::make('inventory_allow_link_interventions')
                    ->label(__('platform.inventory.allow_link_interventions'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.allow_link_interventions'),

                Toggle::make('inventory_enable_storage_zones')
                    ->label(__('platform.inventory.enable_storage_zones'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.enable_storage_zones'),

                Select::make('inventory_zone_mode')
                    ->label(__('platform.inventory.zone_mode'))
                    ->options([
                        'floor_grid' => __('platform.inventory.zone_mode_options.floor_grid'),
                        'bin_rack' => __('platform.inventory.zone_mode_options.bin_rack'),
                    ])
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.zone_mode')
                    ->required(fn (callable $get): bool => (string) $get('key') === 'inventory.zone_mode'),

                Toggle::make('inventory_enable_realtime_monitoring')
                    ->label(__('platform.inventory.enable_realtime_monitoring'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.enable_realtime_monitoring'),

                Toggle::make('inventory_enable_space_optimization')
                    ->label(__('platform.inventory.enable_space_optimization'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.enable_space_optimization'),

                Toggle::make('inventory_enable_operator_workflows')
                    ->label(__('platform.inventory.enable_operator_workflows'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.enable_operator_workflows'),

                Toggle::make('inventory_enable_traceability')
                    ->label(__('platform.inventory.enable_traceability'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.enable_traceability'),

                Toggle::make('inventory_enable_erp_integration')
                    ->label(__('platform.inventory.enable_erp_integration'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.enable_erp_integration'),

                Toggle::make('inventory_enable_rfid_iot')
                    ->label(__('platform.inventory.enable_rfid_iot'))
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'inventory.enable_rfid_iot'),

                Toggle::make('modules_operator_daily_notes')
                    ->label(__('platform.modules.operator.daily_notes'))
                    ->helperText('Se disabilitato, le Note giornaliere non sono visibili nel pannello operatore.')
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'modules.operator.daily_notes'),

                Toggle::make('modules_operator_my_schedule')
                    ->label(__('platform.modules.operator.my_schedule'))
                    ->helperText('Se disabilitato, l\'Agenda non è visibile nel pannello operatore.')
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'modules.operator.my_schedule'),

                Select::make('billing_einvoice_provider')
                    ->label(__('platform.billing.einvoice.provider'))
                    ->options([
                        'disabled' => __('platform.billing.einvoice.provider_options.disabled'),
                        'custom_http' => __('platform.billing.einvoice.provider_options.custom_http'),
                    ])
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'billing.einvoice.provider')
                    ->required(fn (callable $get): bool => (string) $get('key') === 'billing.einvoice.provider'),

                TextInput::make('billing_einvoice_api_key')
                    ->label(__('platform.billing.einvoice.api_key'))
                    ->password()
                    ->revealable()
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'billing.einvoice.api_key'),

                TextInput::make('billing_einvoice_endpoint')
                    ->label(__('platform.billing.einvoice.endpoint'))
                    ->url()
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'billing.einvoice.endpoint'),

                Select::make('interventions_work_mode')
                    ->label(__('platform.interventions.work_mode'))
                    ->options([
                        'work_order' => __('platform.interventions.work_mode_options.work_order'),
                        'customer' => __('platform.interventions.work_mode_options.customer'),
                    ])
                    ->visible(fn (callable $get): bool => (string) $get('key') === 'interventions.work_mode')
                    ->required(fn (callable $get): bool => (string) $get('key') === 'interventions.work_mode'),

                Textarea::make('value_json_raw')
                    ->label('Valore (JSON)')
                    ->helperText('Per chiavi non standard, inserisci un JSON (es. {"value": true}).')
                    ->rows(8)
                    ->visible(function (callable $get): bool {
                        $key = (string) $get('key');
                        return ! in_array($key, [
                            'inventory.trace_mode',
                            'inventory.allow_link_work_orders',
                            'inventory.allow_link_interventions',
                            'inventory.enable_storage_zones',
                            'inventory.zone_mode',
                            'inventory.enable_realtime_monitoring',
                            'inventory.enable_space_optimization',
                            'inventory.enable_operator_workflows',
                            'inventory.enable_traceability',
                            'inventory.enable_erp_integration',
                            'inventory.enable_rfid_iot',
                            'billing.einvoice.provider',
                            'billing.einvoice.api_key',
                            'billing.einvoice.endpoint',
                            'modules.operator.daily_notes',
                            'modules.operator.my_schedule',
                            'interventions.work_mode',
                        ], true);
                    })
                    ->rules(['nullable', 'json']),
            ])
            ;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('key')
                    ->label(__('platform.fields.setting'))
                    ->formatStateUsing(fn (string $state): string => self::humanLabelForKey($state))
                    ->searchable(),
                TextColumn::make('value_display')
                    ->label('Valore')
                    ->state(fn (PlatformSetting $record): string => self::formatValueForDisplay($record))
                    ->badge()
                    ->color(fn (PlatformSetting $record): string => self::badgeColorForValue($record)),
                TextColumn::make('updated_at')
                    ->label('Aggiornato')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                \Filament\Actions\Action::make('quick_edit')
                    ->label('Modifica')
                    ->icon('heroicon-o-pencil')
                    ->slideOver()
                    ->fillForm(function (PlatformSetting $record): array {
                        /** @var mixed $raw */
                        $raw = data_get($record->value_json, 'value');
                        return ['quick_value' => $raw];
                    })
                    ->form(fn (PlatformSetting $record): array => self::quickEditFieldFor((string) $record->key))
                    ->action(function (PlatformSetting $record, array $data): void {
                        $record->update([
                            'value_json' => ['value' => $data['quick_value']],
                            'updated_by_user_id' => Auth::id(),
                        ]);
                    }),
                \Filament\Actions\EditAction::make()
                    ->label('Avanzato')
                    ->icon('heroicon-o-cog-6-tooth'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPlatformSettings::route('/'),
            'edit' => EditPlatformSetting::route('/{record}/edit'),
        ];
    }

    public static function humanLabelForKey(string $key): string
    {
        $translated = __('platform.setting_keys.' . $key);
        if (is_string($translated) && $translated !== '' && $translated !== ('platform.setting_keys.' . $key)) {
            return $translated;
        }

        return $key;
    }

    /**
     * Backward-compatible alias for older references.
     */
    public static function getHumanLabelForKey(string $key): string
    {
        return self::humanLabelForKey($key);
    }

    private static function formatValueForDisplay(PlatformSetting $record): string
    {
        /** @var mixed $value */
        $value = data_get($record->value_json, 'value');

        if ($value === true) {
            return 'Sì';
        }
        if ($value === false) {
            return 'No';
        }
        if ($value === null) {
            return '—';
        }

        /** @var array<string, string> $enumLabels */
        $enumLabels = [
            'both' => 'Entrambe',
            'aggregate' => 'Aggregata',
            'unit_serial' => 'Unità/seriale',
            'floor_grid' => 'Griglia pavimento',
            'bin_rack' => 'Scaffali',
            'disabled' => 'Disabilitato',
            'custom_http' => 'HTTP personalizzato',
            'work_order' => 'Per commessa',
            'customer' => 'Per cliente',
        ];

        $str = (string) $value;

        if (array_key_exists($str, $enumLabels)) {
            return $enumLabels[$str];
        }

        return mb_strlen($str) > 40 ? mb_substr($str, 0, 37) . '…' : ($str !== '' ? $str : '—');
    }

    private static function badgeColorForValue(PlatformSetting $record): string
    {
        /** @var mixed $value */
        $value = data_get($record->value_json, 'value');

        if ($value === true) {
            return 'success';
        }
        if ($value === false) {
            return 'danger';
        }
        if ($value === 'disabled') {
            return 'gray';
        }

        return 'primary';
    }

    /**
     * @return array<int, \Filament\Forms\Components\Field>
     */
    private static function quickEditFieldFor(string $key): array
    {
        $booleanKeys = [
            'inventory.allow_link_work_orders',
            'inventory.allow_link_interventions',
            'inventory.enable_storage_zones',
            'inventory.enable_realtime_monitoring',
            'inventory.enable_space_optimization',
            'inventory.enable_operator_workflows',
            'inventory.enable_traceability',
            'inventory.enable_erp_integration',
            'inventory.enable_rfid_iot',
            'modules.operator.daily_notes',
            'modules.operator.my_schedule',
        ];

        if (in_array($key, $booleanKeys, true)) {
            return [Toggle::make('quick_value')->label(self::humanLabelForKey($key))];
        }

        /** @var array<string, array<string, string>> $selectOptions */
        $selectOptions = [
            'inventory.trace_mode' => [
                'aggregate' => 'Aggregata',
                'unit_serial' => 'Unità/seriale',
                'both' => 'Entrambe',
            ],
            'inventory.zone_mode' => [
                'floor_grid' => 'Griglia a pavimento',
                'bin_rack' => 'Scaffali',
            ],
            'billing.einvoice.provider' => [
                'disabled' => 'Disabilitato',
                'custom_http' => 'HTTP personalizzato',
            ],
            'interventions.work_mode' => [
                'work_order' => 'Per commessa',
                'customer' => 'Per cliente',
            ],
        ];

        if (array_key_exists($key, $selectOptions)) {
            return [
                Select::make('quick_value')
                    ->label(self::humanLabelForKey($key))
                    ->options($selectOptions[$key])
                    ->required(),
            ];
        }

        if ($key === 'billing.einvoice.api_key') {
            return [
                TextInput::make('quick_value')
                    ->label(self::humanLabelForKey($key))
                    ->password()
                    ->revealable(),
            ];
        }

        return [TextInput::make('quick_value')->label(self::humanLabelForKey($key))];
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.platform_settings',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }
}

