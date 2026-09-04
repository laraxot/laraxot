<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Resources\PlatformSettingResource\Pages;

use Filament\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Resources\PlatformSettingResource;
use Modules\Platform\Models\PlatformSetting;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListPlatformSettings extends XotBaseListRecords
{
    protected static string $resource = PlatformSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('seedDefaults')
                ->label(__('platform.actions.seed_defaults'))
                ->requiresConfirmation()
                ->modalDescription(__('platform.actions.seed_defaults_description'))
                ->action(function (): void {
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.trace_mode'],
                        ['value_json' => ['value' => 'both'], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.allow_link_work_orders'],
                        ['value_json' => ['value' => true], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.allow_link_interventions'],
                        ['value_json' => ['value' => true], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.enable_storage_zones'],
                        ['value_json' => ['value' => false], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.zone_mode'],
                        ['value_json' => ['value' => 'floor_grid'], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.enable_realtime_monitoring'],
                        ['value_json' => ['value' => true], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.enable_space_optimization'],
                        ['value_json' => ['value' => false], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.enable_operator_workflows'],
                        ['value_json' => ['value' => false], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.enable_traceability'],
                        ['value_json' => ['value' => true], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.enable_erp_integration'],
                        ['value_json' => ['value' => false], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'inventory.enable_rfid_iot'],
                        ['value_json' => ['value' => false], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'billing.einvoice.provider'],
                        ['value_json' => ['value' => 'disabled'], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'billing.einvoice.api_key'],
                        ['value_json' => ['value' => ''], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'billing.einvoice.endpoint'],
                        ['value_json' => ['value' => ''], 'updated_by_user_id' => Auth::id()],
                    );
                    PlatformSetting::query()->firstOrCreate(
                        ['key' => 'interventions.work_mode'],
                        ['value_json' => ['value' => 'work_order'], 'updated_by_user_id' => Auth::id()],
                    );
                }),
        ];
    }
}
