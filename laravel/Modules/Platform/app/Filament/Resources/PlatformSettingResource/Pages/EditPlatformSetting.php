<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\PlatformSettingResource\Pages;

use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Resources\PlatformSettingResource;
use Modules\Platform\Models\PlatformSetting;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;
use Safe\Exceptions\JsonException;

use function Safe\json_encode;

final class EditPlatformSetting extends XotBaseEditRecord
{
    protected static string $resource = PlatformSettingResource::class;

    public function getTitle(): string
    {
        $record = $this->getRecord();
        if ($record instanceof PlatformSetting) {
            return PlatformSettingResource::humanLabelForKey((string) $record->key);
        }

        return __('platform.settings');
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $key = $this->resolveSettingKey($data);
        $value = $data['value_json'] ?? null;

        if ($key === 'inventory.trace_mode') {
            $data['inventory_trace_mode'] = is_array($value) && isset($value['value'])
                ? $value['value']
                : (is_string($value) ? $value : null);
        } elseif ($key === 'inventory.allow_link_work_orders') {
            $data['inventory_allow_link_work_orders'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'inventory.allow_link_interventions') {
            $data['inventory_allow_link_interventions'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'inventory.enable_storage_zones') {
            $data['inventory_enable_storage_zones'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'inventory.zone_mode') {
            $data['inventory_zone_mode'] = is_array($value) && isset($value['value'])
                ? $value['value']
                : (is_string($value) ? $value : null);
        } elseif ($key === 'inventory.enable_realtime_monitoring') {
            $data['inventory_enable_realtime_monitoring'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'inventory.enable_space_optimization') {
            $data['inventory_enable_space_optimization'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'inventory.enable_operator_workflows') {
            $data['inventory_enable_operator_workflows'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'inventory.enable_traceability') {
            $data['inventory_enable_traceability'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'inventory.enable_erp_integration') {
            $data['inventory_enable_erp_integration'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'inventory.enable_rfid_iot') {
            $data['inventory_enable_rfid_iot'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'billing.einvoice.provider') {
            $data['billing_einvoice_provider'] = is_array($value) && isset($value['value'])
                ? $value['value']
                : (is_string($value) ? $value : null);
        } elseif ($key === 'billing.einvoice.api_key') {
            $data['billing_einvoice_api_key'] = is_array($value) && isset($value['value'])
                ? $value['value']
                : (is_string($value) ? $value : null);
        } elseif ($key === 'billing.einvoice.endpoint') {
            $data['billing_einvoice_endpoint'] = is_array($value) && isset($value['value'])
                ? $value['value']
                : (is_string($value) ? $value : null);
        } elseif ($key === 'modules.operator.daily_notes') {
            $data['modules_operator_daily_notes'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'modules.operator.my_schedule') {
            $data['modules_operator_my_schedule'] = is_array($value) && isset($value['value'])
                ? (bool) $value['value']
                : (bool) $value;
        } elseif ($key === 'interventions.work_mode') {
            $data['interventions_work_mode'] = is_array($value) && isset($value['value'])
                ? $value['value']
                : (is_string($value) ? $value : null);
        } else {
            $data['value_json_raw'] = $value === null
                ? null
                : json_encode($value, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $key = $this->resolveSettingKey($data);

        if ($key === 'inventory.trace_mode') {
            $data['value_json'] = ['value' => (string) ($data['inventory_trace_mode'] ?? 'aggregate')];
        } elseif ($key === 'inventory.allow_link_work_orders') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_allow_link_work_orders'] ?? false)];
        } elseif ($key === 'inventory.allow_link_interventions') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_allow_link_interventions'] ?? false)];
        } elseif ($key === 'inventory.enable_storage_zones') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_enable_storage_zones'] ?? false)];
        } elseif ($key === 'inventory.zone_mode') {
            $data['value_json'] = ['value' => (string) ($data['inventory_zone_mode'] ?? 'floor_grid')];
        } elseif ($key === 'inventory.enable_realtime_monitoring') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_enable_realtime_monitoring'] ?? true)];
        } elseif ($key === 'inventory.enable_space_optimization') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_enable_space_optimization'] ?? false)];
        } elseif ($key === 'inventory.enable_operator_workflows') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_enable_operator_workflows'] ?? false)];
        } elseif ($key === 'inventory.enable_traceability') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_enable_traceability'] ?? true)];
        } elseif ($key === 'inventory.enable_erp_integration') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_enable_erp_integration'] ?? false)];
        } elseif ($key === 'inventory.enable_rfid_iot') {
            $data['value_json'] = ['value' => (bool) ($data['inventory_enable_rfid_iot'] ?? false)];
        } elseif ($key === 'billing.einvoice.provider') {
            $data['value_json'] = ['value' => (string) ($data['billing_einvoice_provider'] ?? 'disabled')];
        } elseif ($key === 'billing.einvoice.api_key') {
            $data['value_json'] = ['value' => (string) ($data['billing_einvoice_api_key'] ?? '')];
        } elseif ($key === 'billing.einvoice.endpoint') {
            $data['value_json'] = ['value' => (string) ($data['billing_einvoice_endpoint'] ?? '')];
        } elseif ($key === 'modules.operator.daily_notes') {
            $data['value_json'] = ['value' => (bool) ($data['modules_operator_daily_notes'] ?? true)];
        } elseif ($key === 'modules.operator.my_schedule') {
            $data['value_json'] = ['value' => (bool) ($data['modules_operator_my_schedule'] ?? true)];
        } elseif ($key === 'interventions.work_mode') {
            $data['value_json'] = ['value' => (string) ($data['interventions_work_mode'] ?? 'work_order')];
        } else {
            $raw = $data['value_json_raw'] ?? null;
            if (is_string($raw)) {
                $trimmed = trim($raw);
                if ($trimmed === '') {
                    $data['value_json'] = null;
                } else {
                    try {
                        /** @var mixed $decoded */
                        $decoded = \Safe\json_decode($trimmed, true);
                    } catch (JsonException) {
                        $decoded = $trimmed;
                    }
                    $data['value_json'] = is_array($decoded) ? $decoded : ['value' => $decoded];
                }
            }
        }

        unset(
            $data['inventory_trace_mode'],
            $data['inventory_allow_link_work_orders'],
            $data['inventory_allow_link_interventions'],
            $data['inventory_enable_storage_zones'],
            $data['inventory_zone_mode'],
            $data['inventory_enable_realtime_monitoring'],
            $data['inventory_enable_space_optimization'],
            $data['inventory_enable_operator_workflows'],
            $data['inventory_enable_traceability'],
            $data['inventory_enable_erp_integration'],
            $data['inventory_enable_rfid_iot'],
            $data['billing_einvoice_provider'],
            $data['billing_einvoice_api_key'],
            $data['billing_einvoice_endpoint'],
            $data['modules_operator_daily_notes'],
            $data['modules_operator_my_schedule'],
            $data['interventions_work_mode'],
            $data['value_json_raw'],
        );

        $data['updated_by_user_id'] = Auth::id();

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function resolveSettingKey(array $data): string
    {
        $key = $data['key'] ?? null;
        if (is_string($key) && $key !== '') {
            return $key;
        }

        $record = $this->getRecord();

        return $record instanceof PlatformSetting ? (string) $record->key : '';
    }
}
