<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\PlatformSettingResource\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Model;
use Modules\Platform\Filament\Resources\PlatformSettingResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Renders `key` plus one typed field per known setting key.
 *
 * The typed field names here (`inventory_trace_mode`,
 * `billing_einvoice_provider`, ...) are load-bearing: they are read/written
 * by `PlatformSettingResource\Pages\EditPlatformSetting::mutateFormDataBeforeFill()`
 * / `mutateFormDataBeforeSave()`, which translate them to/from the record's
 * `value_json` column. Do not rename or drop a field here without updating
 * that page's mutate methods, or saves for that key silently revert to the
 * hardcoded default instead of persisting the admin's input.
 */
final class PlatformSettingForm extends XotBaseResourceForm
{
    protected static string $resource = PlatformSettingResource::class;

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()
                ->columnSpanFull()
                ->components([
                    'key' => TextInput::make('key')
                        ->required()
                        ->disabled(fn (?Model $record): bool => $record !== null)
                        ->dehydrated()
                        ->live()
                        ->visible(fn (?Model $record): bool => $record === null)
                        ->maxLength(190),

                    'key_human' => TextEntry::make('key_human')
                        ->state(function (callable $get): string {
                            $key = PlatformSettingResource::toStringValue($get('key'));

                            return PlatformSettingResource::humanLabelForKey($key);
                        })
                        ->visible(fn (?Model $record): bool => $record !== null),

                    'inventory_trace_mode' => Select::make('inventory_trace_mode')
                        ->options([
                            'aggregate' => __('platform.inventory.trace_mode_options.aggregate'),
                            'unit_serial' => __('platform.inventory.trace_mode_options.unit_serial'),
                            'both' => __('platform.inventory.trace_mode_options.both'),
                        ])
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.trace_mode')
                        ->required(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.trace_mode'),

                    'inventory_allow_link_work_orders' => Toggle::make('inventory_allow_link_work_orders')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.allow_link_work_orders'),

                    'inventory_allow_link_interventions' => Toggle::make('inventory_allow_link_interventions')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.allow_link_interventions'),

                    'inventory_enable_storage_zones' => Toggle::make('inventory_enable_storage_zones')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.enable_storage_zones'),

                    'inventory_zone_mode' => Select::make('inventory_zone_mode')
                        ->options([
                            'floor_grid' => __('platform.inventory.zone_mode_options.floor_grid'),
                            'bin_rack' => __('platform.inventory.zone_mode_options.bin_rack'),
                        ])
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.zone_mode')
                        ->required(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.zone_mode'),

                    'inventory_enable_realtime_monitoring' => Toggle::make('inventory_enable_realtime_monitoring')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.enable_realtime_monitoring'),

                    'inventory_enable_space_optimization' => Toggle::make('inventory_enable_space_optimization')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.enable_space_optimization'),

                    'inventory_enable_operator_workflows' => Toggle::make('inventory_enable_operator_workflows')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.enable_operator_workflows'),

                    'inventory_enable_traceability' => Toggle::make('inventory_enable_traceability')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.enable_traceability'),

                    'inventory_enable_erp_integration' => Toggle::make('inventory_enable_erp_integration')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.enable_erp_integration'),

                    'inventory_enable_rfid_iot' => Toggle::make('inventory_enable_rfid_iot')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'inventory.enable_rfid_iot'),

                    'modules_operator_daily_notes' => Toggle::make('modules_operator_daily_notes')
                        ->helperText('Se disabilitato, le Note giornaliere non sono visibili nel pannello operatore.')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'modules.operator.daily_notes'),

                    'modules_operator_my_schedule' => Toggle::make('modules_operator_my_schedule')
                        ->helperText('Se disabilitato, l\'Agenda non è visibile nel pannello operatore.')
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'modules.operator.my_schedule'),

                    'billing_einvoice_provider' => Select::make('billing_einvoice_provider')
                        ->options([
                            'disabled' => __('platform.billing.einvoice.provider_options.disabled'),
                            'custom_http' => __('platform.billing.einvoice.provider_options.custom_http'),
                        ])
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'billing.einvoice.provider')
                        ->required(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'billing.einvoice.provider'),

                    'billing_einvoice_api_key' => TextInput::make('billing_einvoice_api_key')
                        ->password()
                        ->revealable()
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'billing.einvoice.api_key'),

                    'billing_einvoice_endpoint' => TextInput::make('billing_einvoice_endpoint')
                        ->url()
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'billing.einvoice.endpoint'),

                    'interventions_work_mode' => Select::make('interventions_work_mode')
                        ->options([
                            'work_order' => __('platform.interventions.work_mode_options.work_order'),
                            'customer' => __('platform.interventions.work_mode_options.customer'),
                        ])
                        ->visible(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'interventions.work_mode')
                        ->required(fn (callable $get): bool => PlatformSettingResource::toStringValue($get('key')) === 'interventions.work_mode'),

                    'value_json_raw' => Textarea::make('value_json_raw')
                        ->helperText('Per chiavi non standard, inserisci un JSON (es. {"value": true}).')
                        ->rows(8)
                        ->visible(function (callable $get): bool {
                            $key = PlatformSettingResource::toStringValue($get('key'));

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
                ->columns(1),
        ];
    }
}
