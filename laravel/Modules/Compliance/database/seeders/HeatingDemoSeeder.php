<?php

declare(strict_types=1);

namespace Modules\Compliance\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Models\Boiler;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Compliance\Models\MaintenanceControl;

/**
 * Realistic demo data for the boiler-maintenance vertical: heating systems with
 * boilers and past controls, and next-due dates spread across overdue / expiring
 * / compliant so the deadlines page and colors are populated. Idempotent.
 */
final class HeatingDemoSeeder extends Seeder
{
    public function run(): void
    {
        $customerIds = $this->customerIds();

        $blueprints = [
            ['code' => 'IMP-DEMO-1', 'brand' => 'Ariston', 'model' => 'Clas One Wifi 24', 'power' => 24.0, 'fuel' => 'metano', 'maint_due' => '-40 days', 'eff_due' => '+8 months'],
            ['code' => 'IMP-DEMO-2', 'brand' => 'Vaillant', 'model' => 'ecoTEC plus', 'power' => 28.0, 'fuel' => 'metano', 'maint_due' => '+20 days', 'eff_due' => '+2 years'],
            ['code' => 'IMP-DEMO-3', 'brand' => 'Baxi', 'model' => 'Luna Duo-tec', 'power' => 33.0, 'fuel' => 'gpl', 'maint_due' => '+7 months', 'eff_due' => '+18 months'],
            ['code' => 'IMP-DEMO-4', 'brand' => 'Immergas', 'model' => 'Victrix', 'power' => 120.0, 'fuel' => 'metano', 'maint_due' => '-5 days', 'eff_due' => '-2 days'],
        ];

        foreach ($blueprints as $index => $bp) {
            $customerId = $customerIds[$index % count($customerIds)];

            /** @var HeatingSystem $system */
            $system = HeatingSystem::query()->updateOrCreate(
                ['code' => $bp['code']],
                [
                    'customer_id' => $customerId,
                    'customer_site_id' => DB::table('customer_sites')->where('customer_id', $customerId)->value('id'),
                    'description' => 'Impianto autonomo '.$bp['brand'],
                    'fuel_type' => $bp['fuel'],
                    'total_power_kw' => $bp['power'],
                    'plant_registry_code' => 'CAT-'.str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT),
                    'installation_date' => Carbon::parse('-6 years'),
                    'is_active' => true,
                ],
            );

            /** @var Boiler $boiler */
            $boiler = Boiler::query()->updateOrCreate(
                ['heating_system_id' => $system->id, 'serial_number' => 'SN-'.$bp['code']],
                [
                    'brand' => $bp['brand'],
                    'model' => $bp['model'],
                    'generator_type' => 'condensazione',
                    'fuel_type' => $bp['fuel'],
                    'power_kw' => $bp['power'],
                    'installation_date' => Carbon::parse('-6 years'),
                    'next_maintenance_due_on' => Carbon::parse($bp['maint_due']),
                    'next_efficiency_check_due_on' => Carbon::parse($bp['eff_due']),
                ],
            );

            MaintenanceControl::query()->updateOrCreate(
                ['boiler_id' => $boiler->id, 'control_type' => 'efficiency_check'],
                [
                    'heating_system_id' => $system->id,
                    'performed_on' => Carbon::parse('-1 year'),
                    'outcome' => 'conforme',
                    'combustion_efficiency_percent' => 95.0 + $index,
                    'co2_percent' => 9.2,
                    'o2_percent' => 4.8,
                    'co_ppm' => 40,
                    'flue_gas_temp_c' => 120.0,
                    'smoke_index' => 1,
                    'bollino_code' => 'BOLL-'.$bp['code'],
                    'bollino_paid' => true,
                    'next_due_on' => Carbon::parse($bp['eff_due']),
                    'notes' => 'Controllo di efficienza energetica eseguito regolarmente.',
                ],
            );
        }
    }

    /**
     * @return list<int>
     */
    private function customerIds(): array
    {
        /** @var list<int> $ids */
        $ids = DB::table('customers')->orderBy('id')->limit(4)->pluck('id')->map(static fn ($id): int => (int) $id)->all();

        if ($ids === []) {
            $ids = [(int) DB::table('customers')->insertGetId([
                'legal_name' => 'Condominio Demo Riscaldamento',
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ])];
        }

        return $ids;
    }
}
