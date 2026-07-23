<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

final class OperationalLookupsSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::now();

        DB::transaction(function () use ($now): void {
            $this->seedWorkOrderStatuses($now);
            $this->seedContactRoles($now);
            $this->seedCatalogLookups($now);
            $this->seedPlatformSettings($now);
        });
    }

    private function seedWorkOrderStatuses(CarbonImmutable $now): void
    {
        if (! Schema::hasTable('work_order_statuses')) {
            return;
        }

        $statuses = [
            ['code' => 'draft', 'name' => 'Bozza', 'color' => 'gray', 'is_terminal' => false, 'sort_order' => 10],
            ['code' => 'scheduled', 'name' => 'Programmato', 'color' => 'blue', 'is_terminal' => false, 'sort_order' => 20],
            ['code' => 'in_progress', 'name' => 'In lavorazione', 'color' => 'amber', 'is_terminal' => false, 'sort_order' => 30],
            ['code' => 'completed', 'name' => 'Completato', 'color' => 'green', 'is_terminal' => true, 'sort_order' => 40],
            ['code' => 'cancelled', 'name' => 'Annullato', 'color' => 'red', 'is_terminal' => true, 'sort_order' => 50],
        ];

        foreach ($statuses as $status) {
            DB::table('work_order_statuses')->updateOrInsert(
                ['code' => $status['code']],
                $status + ['created_at' => $now, 'updated_at' => $now],
            );
        }
    }

    private function seedContactRoles(CarbonImmutable $now): void
    {
        if (! Schema::hasTable('contact_roles')) {
            return;
        }

        foreach ([
            'Titolare',
            'Amministratore',
            'Amministrazione',
            'Responsabile commesse',
            'Ufficio tecnico',
            'Referente tecnico',
            'Capocantiere',
        ] as $roleName) {
            $this->insertIfMissing(
                'contact_roles',
                ['name' => $roleName],
                ['created_at' => $now, 'updated_at' => $now],
            );
        }
    }

    private function seedCatalogLookups(CarbonImmutable $now): void
    {
        if (Schema::hasTable('catalog_frequencies')) {
            foreach ([
                ['name' => 'A richiesta', 'sort_order' => 10],
                ['name' => 'Una tantum', 'sort_order' => 20],
                ['name' => 'Mensile', 'sort_order' => 30],
                ['name' => 'Trimestrale', 'sort_order' => 40],
                ['name' => 'Annuale', 'sort_order' => 50],
            ] as $frequency) {
                $this->insertIfMissing(
                    'catalog_frequencies',
                    ['name' => $frequency['name']],
                    $frequency + ['created_at' => $now, 'updated_at' => $now],
                );
            }
        }

        if (Schema::hasTable('catalog_measure_units')) {
            foreach ([
                ['name' => 'Pezzo', 'symbol' => 'pz'],
                ['name' => 'Ora', 'symbol' => 'h'],
                ['name' => 'Giorno', 'symbol' => 'gg'],
                ['name' => 'Metro', 'symbol' => 'm'],
                ['name' => 'Metro quadrato', 'symbol' => 'mq'],
                ['name' => 'Metro cubo', 'symbol' => 'mc'],
                ['name' => 'Kg', 'symbol' => 'kg'],
                ['name' => 'Litro', 'symbol' => 'l'],
                ['name' => 'Servizio', 'symbol' => null],
            ] as $measureUnit) {
                $this->insertIfMissing(
                    'catalog_measure_units',
                    ['name' => $measureUnit['name']],
                    $measureUnit + ['created_at' => $now, 'updated_at' => $now],
                );
            }
        }

        if (Schema::hasTable('catalog_part_types')) {
            foreach ([
                'Materiali',
                'Lavorazioni',
                'Servizi',
                'Noleggi',
                'Ricambi',
            ] as $partType) {
                $this->insertIfMissing(
                    'catalog_part_types',
                    ['name' => $partType],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }
        }

        if (Schema::hasTable('catalog_models')) {
            foreach ([
                'Generico',
                'Standard',
                'Professionale',
            ] as $modelName) {
                $this->insertIfMissing(
                    'catalog_models',
                    ['name' => $modelName],
                    ['created_at' => $now, 'updated_at' => $now],
                );
            }
        }
    }

    private function seedPlatformSettings(CarbonImmutable $now): void
    {
        if (! Schema::hasTable('platform_settings')) {
            return;
        }

        foreach ([
            'inventory.trace_mode' => ['value' => 'both'],
            'inventory.allow_link_work_orders' => ['value' => true],
            'inventory.allow_link_interventions' => ['value' => true],
            'inventory.enable_storage_zones' => ['value' => false],
            'inventory.zone_mode' => ['value' => 'floor_grid'],
            'inventory.enable_realtime_monitoring' => ['value' => true],
            'inventory.enable_space_optimization' => ['value' => false],
            'inventory.enable_operator_workflows' => ['value' => false],
            'inventory.enable_traceability' => ['value' => true],
            'inventory.enable_erp_integration' => ['value' => false],
            'inventory.enable_rfid_iot' => ['value' => false],
            'modules.operator.daily_notes' => ['value' => true],
            'modules.operator.my_schedule' => ['value' => true],
            'billing.einvoice.provider' => ['value' => 'disabled'],
            'billing.einvoice.api_key' => ['value' => ''],
            'billing.einvoice.endpoint' => ['value' => ''],
        ] as $key => $valueJson) {
            $this->insertIfMissing(
                'platform_settings',
                ['key' => $key],
                [
                    'value_json' => json_encode($valueJson, JSON_UNESCAPED_UNICODE),
                    'updated_by_user_id' => null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );
        }
    }

    /** @param array<string, mixed> $attributes
     *  @param array<string, mixed> $values
     */
    private function insertIfMissing(string $table, array $attributes, array $values): void
    {
        $exists = DB::table($table)->where($attributes)->exists();

        if ($exists) {
            return;
        }

        DB::table($table)->insert($attributes + $values);
    }
}
