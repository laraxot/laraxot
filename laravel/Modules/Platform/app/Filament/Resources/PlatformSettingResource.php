<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Resources\PlatformSettingResource\Pages\EditPlatformSetting;
use Modules\Platform\Filament\Resources\PlatformSettingResource\Pages\ListPlatformSettings;
use Modules\Platform\Models\PlatformSetting;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class PlatformSettingResource extends XotBaseResource
{
    protected static ?string $model = PlatformSetting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationLabel = null;

    protected static ?string $pluralModelLabel = null;

    protected static ?string $modelLabel = null;

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): string
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

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
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

    public static function getPages(): array
    {
        return [
            'index' => ListPlatformSettings::route('/'),
            'edit' => EditPlatformSetting::route('/{record}/edit'),
        ];
    }

    public static function humanLabelForKey(string $key): string
    {
        $translated = __('platform.setting_keys.'.$key);
        if (is_string($translated) && $translated !== '' && $translated !== ('platform.setting_keys.'.$key)) {
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

    /**
     * Narrows a Filament form/table `mixed` value down to a safe string
     * representation, without ever casting a non-scalar value.
     *
     * Public: consumed cross-class by the canonical
     * `PlatformSettingResource\Schemas\PlatformSettingForm` and
     * `PlatformSettingResource\Tables\PlatformSettingsTable`.
     */
    public static function toStringValue(mixed $value): string
    {
        if (is_string($value)) {
            return $value;
        }

        return is_scalar($value) ? (string) $value : '';
    }

    /**
     * Public: consumed cross-class by
     * `PlatformSettingResource\Tables\PlatformSettingsTable`.
     */
    public static function formatValueForDisplay(PlatformSetting $record): string
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

        $str = self::toStringValue($value);

        if (array_key_exists($str, $enumLabels)) {
            return $enumLabels[$str];
        }

        return mb_strlen($str) > 40 ? mb_substr($str, 0, 37).'…' : ($str !== '' ? $str : '—');
    }

    /**
     * Public: consumed cross-class by
     * `PlatformSettingResource\Tables\PlatformSettingsTable`.
     */
    public static function badgeColorForValue(PlatformSetting $record): string
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
     * Public: consumed cross-class by
     * `PlatformSettingResource\Tables\PlatformSettingsTable`.
     *
     * @return array<int, Field>
     */
    public static function quickEditFieldFor(string $key): array
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
