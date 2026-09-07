<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\DeliveryNoteLine;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Models\Supplier;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Compliance\Actions\CalculateFGasObligationAction;
use Modules\Compliance\DTO\ValueObjects\ThermalPlantRegistry;
use Modules\Compliance\Models\Boiler;
use Modules\Compliance\Models\FGasLeakCheck;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Compliance\Models\RefrigerantCircuit;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;
use Safe\DateTimeImmutable as SafeDateTimeImmutable;
use Spatie\Permission\Models\Role;

/**
 * Demo for a boiler-and-plumbing firm working mostly for private customers.
 *
 * Run with:
 * `php artisan db:seed --class="Modules\Platform\Database\Seeders\BoutiqueIdraulicaDemoSeeder"`
 *
 * It seeds one coherent picture rather than scattered rows, built so the three
 * financial-control questions all have something to answer: an installed base
 * with maintenance falling due, jobs whose margin differs sharply, invoices at
 * several stages of lateness, and supplier bills landing before the customer
 * money arrives — which is what puts a dip in the cash-flow projection.
 *
 * Dates hang off today rather than a fixed point, because ageing is measured
 * against the current date: pinning them would leave every invoice sitting in
 * the "over 90 days" band a few months from now.
 *
 * All data is fictitious. The seeder is idempotent.
 */
final class BoutiqueIdraulicaDemoSeeder extends Seeder
{
    /** Drives which regional register the seeded plants belong to. */
    private const REGION = 'emilia-romagna';

    public function run(): void
    {
        $today = CarbonImmutable::today();

        $team = $this->seedTeam();
        $statuses = $this->seedWorkOrderStatuses();
        $customers = $this->seedCustomers();

        $this->seedHeatingSystems($customers, $today);
        $this->seedRefrigerantCircuits($customers, $today);

        $profitable = $this->seedProfitableJob($customers['rossi'], $team['technician'], $today);
        $losing = $this->seedLosingJob($customers['fontana'], $team['technician'], $today);
        $unbilled = $this->seedUnbilledJob($customers['marchetti'], $team['technician'], $today);

        $profitable->forceFill(['status_id' => $statuses['invoiced']])->save();
        $losing->forceFill(['status_id' => $statuses['invoiced']])->save();
        // Lavoro finito e collaudato ma non ancora fatturato: e' la riga che in
        // Controllo di gestione compare come "non ancora fatturata".
        $unbilled->forceFill(['status_id' => $statuses['commissioned']])->save();

        $this->seedCustomerInvoices($customers, $profitable, $losing, $unbilled, $today);
        $this->seedSupplierBills($today);
    }

    /**
     * @return array{owner: User, technician: User}
     */
    private function seedTeam(): array
    {
        $owner = $this->demoUser('titolare@boutiqueidraulica.demo', 'Roberto Berlini', 'admin');

        $technician = $this->demoUser('andrea.tecnico@boutiqueidraulica.demo', 'Andrea Ferrari', 'operator');
        $technician->forceFill(['manager_id' => $owner->id])->save();

        return ['owner' => $owner, 'technician' => $technician];
    }

    /**
     * A demo account is only usable if it can actually get in, and Filament
     * reports "wrong credentials" for both a bad password and a user the panel
     * refuses — so a seeded user missing its approval or its role looks exactly
     * like a typo. Both are set here, and the password is reset on every run so
     * an account left over from an earlier seed cannot lock anyone out.
     */
    private function demoUser(string $email, string $name, string $role): User
    {
        $user = User::query()->firstOrCreate(['email' => $email], ['name' => $name]);

        $user->forceFill([
            'name' => $name,
            'password' => Hash::make('password'),
            'approved_at' => $user->approved_at ?? CarbonImmutable::now(),
            'email_verified_at' => $user->email_verified_at ?? CarbonImmutable::now(),
        ])->save();

        Role::findOrCreate($role);
        $user->syncRoles([$role]);

        return $user;
    }

    /**
     * The phases a boiler job actually passes through in this trade, which are
     * not the generic draft/in-progress/done. Two of them earn their place: the
     * split between quote sent and quote accepted, because that gap is where
     * work is won or lost, and the split between commissioned and invoiced,
     * because finished work sitting uninvoiced is the commonest way a small
     * firm loses money it has already earned.
     *
     * The generic set stays in place — this only adds to it, so nothing that
     * already points at a status breaks.
     *
     * @return array<string, int>
     */
    private function seedWorkOrderStatuses(): array
    {
        $now = CarbonImmutable::now();

        $phases = [
            ['site_survey', 'Sopralluogo', 'gray', false, 110],
            ['quote_sent', 'Preventivo inviato', 'blue', false, 120],
            ['quote_accepted', 'Preventivo accettato', 'indigo', false, 130],
            ['materials_ordered', 'Materiale ordinato', 'purple', false, 140],
            ['on_site', 'In cantiere', 'amber', false, 150],
            ['commissioned', 'Collaudato', 'teal', false, 160],
            ['invoiced', 'Fatturato', 'green', true, 170],
        ];

        $ids = [];

        foreach ($phases as [$code, $name, $color, $isTerminal, $sortOrder]) {
            DB::table('work_order_statuses')->updateOrInsert(
                ['code' => $code],
                [
                    'name' => $name,
                    'color' => $color,
                    'is_terminal' => $isTerminal,
                    'sort_order' => $sortOrder,
                    'created_at' => $now,
                    'updated_at' => $now,
                ],
            );

            $ids[$code] = self::toInt(DB::table('work_order_statuses')->where('code', $code)->value('id'));
        }

        return $ids;
    }

    /**
     * @return array<string, Customer>
     */
    private function seedCustomers(): array
    {
        $definitions = [
            'rossi' => ['Rossi Mario', 'Via Giardini 118', 'Modena', 'MO', '41124', '059 221340'],
            'fontana' => ['Fontana Giulia', 'Via Sant\'Antonio 7', 'Formigine', 'MO', '41043', '059 557210'],
            'marchetti' => ['Marchetti Paolo', 'Via Nazionale 244', 'Maranello', 'MO', '41053', '0536 941120'],
            'gabbiano' => ['Condominio Il Gabbiano', 'Viale Storchi 42', 'Modena', 'MO', '41121', '059 330980'],
        ];

        $customers = [];

        foreach ($definitions as $key => [$name, $address, $city, $province, $postalCode, $phone]) {
            $customers[$key] = Customer::query()->firstOrCreate(
                ['legal_name' => $name],
                [
                    'email' => $key.'@clienti.demo',
                    'phone' => $phone,
                    'address_line1' => $address,
                    'city' => $city,
                    'province' => $province,
                    'postal_code' => $postalCode,
                    'country_code' => 'IT',
                    'is_active' => true,
                ],
            );
        }

        return $customers;
    }

    /**
     * The installed base. One control is already late, one falls inside the
     * warning window and one is comfortably clear, so the deadlines page shows
     * all three states instead of a single colour.
     *
     * @param  array<string, Customer>  $customers
     */
    private function seedHeatingSystems(array $customers, CarbonImmutable $today): void
    {
        $definitions = [
            ['IMP-2026-001', 'rossi', 'Caldaia murale appartamento', 'Vaillant', 'ecoTEC plus VMW 246',
                '21123400123456', 24.0, $today->subMonths(14), $today->subDays(60)],
            ['IMP-2026-002', 'fontana', 'Caldaia + pompa di calore', 'Daikin', 'Altherma 3 R',
                'DK2024889120', 16.0, $today->subMonths(8), $today->addDays(35)],
            ['IMP-2026-003', 'marchetti', 'Caldaia a condensazione', 'Baxi', 'Luna Duo-tec+ 28',
                'BX2025771003', 28.0, $today->subMonths(5), $today->addMonths(7)],
            ['IMP-2026-004', 'gabbiano', 'Centrale termica condominiale', 'Riello', 'Condens 100',
                'RL2023110455', 100.0, $today->subMonths(20), $today->subDays(12)],
        ];

        foreach ($definitions as [$code, $customerKey, $description, $brand, $model, $serial, $powerKw, $installedOn, $nextMaintenance]) {
            $system = HeatingSystem::query()->firstOrCreate(
                ['code' => $code],
                [
                    'customer_id' => $customers[$customerKey]->id,
                    'description' => $description,
                    'fuel_type' => 'metano',
                    'total_power_kw' => $powerKw,
                    'plant_registry_code' => 'MO'.str_pad((string) random_int(1, 999999), 8, '0', STR_PAD_LEFT),
                    'region' => self::REGION,
                    'registry_code' => ThermalPlantRegistry::forRegion(self::REGION),
                    'installation_date' => $installedOn,
                    'is_active' => true,
                ],
            );

            Boiler::query()->firstOrCreate(
                ['heating_system_id' => $system->id, 'serial_number' => $serial],
                [
                    'brand' => $brand,
                    'model' => $model,
                    'generator_type' => 'condensazione',
                    'fuel_type' => 'metano',
                    'power_kw' => $powerKw,
                    'installation_date' => $installedOn,
                    'maintenance_interval_months' => 12,
                    'efficiency_check_interval_months' => 24,
                    'next_maintenance_due_on' => $nextMaintenance,
                    'next_efficiency_check_due_on' => $nextMaintenance->addMonths(12),
                ],
            );
        }
    }

    /**
     * Refrigerant circuits spanning the three F-Gas outcomes: one sealed split
     * that falls outside the regime, one that needs an annual check and is
     * already late, and one large enough to be checked twice a year. Without
     * the spread the page shows a single verdict and proves nothing.
     *
     * @param  array<string, Customer>  $customers
     */
    private function seedRefrigerantCircuits(array $customers, CarbonImmutable $today): void
    {
        $obligations = new CalculateFGasObligationAction();

        $definitions = [
            // Sealed domestic split: below the threshold, no duty.
            ['FGAS-001', 'rossi', 'Climatizzatore monosplit camera', 'R32', 1.2, true, false, $today->subMonths(6)],
            // Heat pump: past 5 t CO2eq, annual check, last one over a year ago.
            ['FGAS-002', 'fontana', 'Pompa di calore aria-acqua', 'R410A', 4.0, false, false, $today->subMonths(14)],
            // Condominium chiller: into the 50 t band, six-monthly.
            ['FGAS-003', 'gabbiano', 'Gruppo frigo centrale termica', 'R134a', 38.0, false, false, $today->subMonths(4)],
        ];

        foreach ($definitions as [$code, $customerKey, $description, $gas, $chargeKg, $hermetic, $detection, $lastCheck]) {
            $obligation = $obligations->execute(
                refrigerantCode: $gas,
                chargeKg: $chargeKg,
                lastCheckedOn: new SafeDateTimeImmutable($lastCheck->toDateString()),
                hasLeakDetectionSystem: $detection,
                isHermeticallySealed: $hermetic,
            );

            $circuit = RefrigerantCircuit::query()->firstOrCreate(
                ['code' => $code],
                [
                    'customer_id' => $customers[$customerKey]->id,
                    'description' => $description,
                    'location' => 'Sede cliente',
                    'refrigerant_code' => $gas,
                    'charge_kg' => $chargeKg,
                    'co2_equivalent_tonnes' => $obligation->co2EquivalentTonnes,
                    'is_hermetically_sealed' => $hermetic,
                    'has_leak_detection_system' => $detection,
                    'check_interval_months' => $obligation->checkIntervalMonths,
                    'last_leak_check_on' => $lastCheck,
                    'next_leak_check_due_on' => $obligation->nextCheckDueOn?->format('Y-m-d'),
                    'installation_date' => $lastCheck->subYears(2),
                    'is_active' => true,
                ],
            );

            if (! $obligation->requiresLeakChecks()) {
                continue;
            }

            FGasLeakCheck::query()->firstOrCreate(
                ['refrigerant_circuit_id' => $circuit->id, 'performed_on' => $lastCheck],
                [
                    'outcome' => 'no_leak',
                    'method' => 'direct',
                    'gas_added_kg' => 0,
                    'gas_recovered_kg' => 0,
                    'operator_certificate' => 'FGAS-IT-2024-118820',
                    'next_due_on' => $obligation->nextCheckDueOn?->format('Y-m-d'),
                ],
            );
        }
    }

    /**
     * A job that went the way it was meant to: quoted hours held, materials as
     * expected.
     */
    private function seedProfitableJob(Customer $customer, User $technician, CarbonImmutable $today): WorkOrder
    {
        $workOrder = WorkOrder::query()->firstOrCreate(
            ['code' => 'CM-2026-101'],
            [
                'customer_id' => $customer->id,
                'title' => 'Sostituzione caldaia murale',
                'description' => 'Rimozione caldaia esistente, posa condensazione 24 kW, scarico condensa.',
                'planned_minutes' => 600,
                'opened_at' => $today->subDays(50),
                'scheduled_at' => $today->subDays(45),
            ],
        );

        $intervention = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $today->subDays(45)->setTime(8, 0)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $today->subDays(45)->setTime(17, 0),
                'work_minutes' => 540,
                'amount' => 620.00,
                'callout_fee' => 45.00,
                'travel_km' => 12,
                'notes' => 'Sostituzione completata in giornata. Collaudo e messa in servizio ok.',
            ],
        );

        $this->materialLine($intervention, 'Caldaia a condensazione 24 kW', 1, 'pz', 1450.00);
        $this->materialLine($intervention, 'Kit scarico condensa', 1, 'kit', 95.00);

        $this->deliveryNote($workOrder, 'DDT-2026-5510', $today->subDays(47), [
            ['Caldaia a condensazione 24 kW', 1, 890.00],
            ['Kit scarico condensa', 1, 52.00],
        ]);

        $intervention->refreshEconomicTotals();

        return $workOrder;
    }

    /**
     * The job that quietly lost money: two extra days on site and materials well
     * past what was allowed for. It is the row the owner needs to see.
     */
    private function seedLosingJob(Customer $customer, User $technician, CarbonImmutable $today): WorkOrder
    {
        $workOrder = WorkOrder::query()->firstOrCreate(
            ['code' => 'CM-2026-108'],
            [
                'customer_id' => $customer->id,
                'title' => 'Impianto pompa di calore + rifacimento distribuzione',
                'description' => 'Posa pompa di calore aria-acqua, rifacimento collettori e distribuzione.',
                'planned_minutes' => 1440,
                'opened_at' => $today->subDays(40),
                'scheduled_at' => $today->subDays(32),
            ],
        );

        $first = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $today->subDays(32)->setTime(8, 0)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $today->subDays(32)->setTime(18, 30),
                'work_minutes' => 630,
                'amount' => 740.00,
                'callout_fee' => 0,
                'travel_km' => 22,
                'notes' => 'Posa unità esterna. Staffaggio più complesso del previsto.',
            ],
        );

        $second = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $today->subDays(28)->setTime(8, 0)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $today->subDays(28)->setTime(19, 0),
                'work_minutes' => 660,
                'amount' => 780.00,
                'callout_fee' => 0,
                'travel_km' => 22,
                'notes' => 'Trovate tubazioni ammalorate sotto massetto: rifatta tutta la distribuzione.',
            ],
        );

        $third = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $today->subDays(24)->setTime(8, 0)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $today->subDays(24)->setTime(16, 0),
                'work_minutes' => 480,
                'amount' => 0,
                'callout_fee' => 0,
                'travel_km' => 22,
                'notes' => 'Ripasso e collaudo. Non fatturato al cliente.',
            ],
        );

        $this->materialLine($first, 'Pompa di calore aria-acqua 8 kW', 1, 'pz', 4200.00);
        $this->materialLine($second, 'Collettore in ottone 8 vie', 2, 'pz', 168.00);
        $this->materialLine($second, 'Tubo multistrato 20mm', 120, 'm', 5.20);

        // Il fornitore ha fatturato piu' del preventivato: e' qui che il margine se ne va.
        $this->deliveryNote($workOrder, 'DDT-2026-5588', $today->subDays(33), [
            ['Pompa di calore aria-acqua 8 kW', 1, 3350.00],
            ['Collettore in ottone 8 vie', 2, 121.00],
        ]);

        $this->deliveryNote($workOrder, 'DDT-2026-5604', $today->subDays(29), [
            ['Tubo multistrato 20mm', 160, 3.90],
            ['Raccorderia e minuteria', 1, 340.00],
            ['Isolante per tubazioni', 60, 4.10],
        ]);

        $first->refreshEconomicTotals();
        $second->refreshEconomicTotals();
        $third->refreshEconomicTotals();

        return $workOrder;
    }

    /**
     * Work already done and costed but not invoiced yet: margin percent has
     * nothing to divide by, which is the case the page reports rather than
     * guesses.
     */
    private function seedUnbilledJob(Customer $customer, User $technician, CarbonImmutable $today): WorkOrder
    {
        $workOrder = WorkOrder::query()->firstOrCreate(
            ['code' => 'CM-2026-115'],
            [
                'customer_id' => $customer->id,
                'title' => 'Manutenzione straordinaria impianto gas',
                'description' => 'Revisione tratti interrati e sostituzione valvolame.',
                'planned_minutes' => 480,
                'opened_at' => $today->subDays(9),
                'scheduled_at' => $today->subDays(6),
            ],
        );

        $intervention = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $today->subDays(6)->setTime(8, 30)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $today->subDays(6)->setTime(15, 30),
                'work_minutes' => 420,
                'amount' => 0,
                'callout_fee' => 0,
                'travel_km' => 15,
                'notes' => 'Lavoro concluso, da fatturare.',
            ],
        );

        $this->deliveryNote($workOrder, 'DDT-2026-5701', $today->subDays(7), [
            ['Valvolame gas assortito', 1, 380.00],
        ]);

        $intervention->refreshEconomicTotals();

        return $workOrder;
    }

    /**
     * Customer invoices spread across the ageing bands, so the report is not a
     * single row: one settled, one just late, one badly late, one still to come.
     *
     * @param  array<string, Customer>  $customers
     */
    private function seedCustomerInvoices(
        array $customers,
        WorkOrder $profitable,
        WorkOrder $losing,
        WorkOrder $unbilled,
        CarbonImmutable $today,
    ): void {
        $definitions = [
            ['FT/2026/0412', 'rossi', $profitable->id, $today->subDays(75), $today->subDays(45), 2745.00, 2745.00, $today->subDays(44)],
            ['FT/2026/0455', 'fontana', $losing->id, $today->subDays(60), $today->subDays(30), 6890.00, 2000.00, null],
            ['FT/2026/0331', 'gabbiano', null, $today->subDays(150), $today->subDays(120), 3120.00, 0.00, null],
            ['FT/2026/0498', 'marchetti', $unbilled->id, $today->subDays(4), $today->addDays(26), 1480.00, 0.00, null],
        ];

        foreach ($definitions as [$number, $customerKey, $workOrderId, $issuedOn, $dueOn, $total, $paidAmount, $paidOn]) {
            Invoice::query()->firstOrCreate(
                ['number' => $number],
                [
                    'customer_id' => $customers[$customerKey]->id,
                    'work_order_id' => $workOrderId,
                    'issued_on' => $issuedOn,
                    'due_on' => $dueOn,
                    'paid_on' => $paidOn,
                    'total' => $total,
                    'paid_amount' => $paidAmount,
                ],
            );
        }
    }

    /**
     * Supplier bills falling due before the customer money lands. Without them
     * the projection only ever climbs, and the low point — the number worth
     * looking at — never appears.
     */
    private function seedSupplierBills(CarbonImmutable $today): void
    {
        $supplier = Supplier::query()->firstOrCreate(
            ['legal_name' => 'Idroforniture Emilia Srl'],
            [
                'vat_number' => '03112450368',
                'email' => 'amministrazione@idroforniture.demo',
                'phone' => '059 445120',
                'is_active' => true,
            ],
        );

        $definitions = [
            ['FPA-2026-1180', $today->subDays(35), $today->addDays(8), 4820.00],
            ['FPA-2026-1204', $today->subDays(20), $today->addDays(22), 2310.00],
            ['FPA-2026-1240', $today->subDays(6), $today->addDays(54), 1190.00],
        ];

        foreach ($definitions as [$number, $issuedOn, $dueOn, $total]) {
            SupplierInvoice::query()->firstOrCreate(
                ['supplier_id' => $supplier->id, 'number' => $number],
                [
                    'issued_on' => $issuedOn,
                    'due_on' => $dueOn,
                    'total' => $total,
                    'paid_amount' => 0,
                    'status_code' => 'pending',
                ],
            );
        }
    }

    private function materialLine(
        Intervention $intervention,
        string $description,
        float $quantity,
        string $unit,
        float $unitPrice,
    ): void {
        InterventionMaterialLine::query()->firstOrCreate(
            ['intervention_id' => $intervention->id, 'description' => $description],
            [
                'quantity' => $quantity,
                'unit' => $unit,
                'unit_price' => $unitPrice,
                'line_total' => round($quantity * $unitPrice, 2),
            ],
        );
    }

    /**
     * @param  list<array{0: string, 1: float|int, 2: float}>  $lines
     */
    private function deliveryNote(WorkOrder $workOrder, string $number, CarbonImmutable $receivedOn, array $lines): void
    {
        $supplier = Supplier::query()->firstOrCreate(
            ['legal_name' => 'Idroforniture Emilia Srl'],
            ['vat_number' => '03112450368', 'is_active' => true],
        );

        $note = DeliveryNote::query()->firstOrCreate(
            ['supplier_id' => $supplier->id, 'number' => $number],
            [
                'work_order_id' => $workOrder->id,
                'received_on' => $receivedOn,
            ],
        );

        foreach ($lines as [$description, $quantity, $unitPrice]) {
            DeliveryNoteLine::query()->firstOrCreate(
                ['delivery_note_id' => $note->id, 'description' => $description],
                ['quantity' => $quantity, 'unit_price' => $unitPrice],
            );
        }
    }

    /**
     * Narrows a `mixed` DB/query-builder value to `int`, without ever
     * blindly casting a non-numeric value.
     */
    private static function toInt(mixed $value, int $default = 0): int
    {
        if (is_int($value)) {
            return $value;
        }

        return is_numeric($value) ? (int) $value : $default;
    }
}
