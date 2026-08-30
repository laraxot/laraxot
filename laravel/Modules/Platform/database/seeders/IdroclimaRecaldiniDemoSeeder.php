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
 * Demo for a heating and air-conditioning firm in the eastern Milan belt.
 *
 * Run with:
 * `php artisan db:seed --class="Modules\Platform\Database\Seeders\IdroclimaRecaldiniDemoSeeder"`
 *
 * Shaped around the one thing this kind of firm is sitting on without seeing
 * it: an installed base of boilers and heat pumps whose maintenance nobody is
 * tracking. Six systems spread across overdue, due soon and clear, so the
 * deadlines page opens on red rather than on a wall of green.
 *
 * Lombardy, so the register is CURIT — the same code that would be CRITER one
 * province east. Registers follow the plant's location, never the company.
 *
 * All data is fictitious. The seeder is idempotent.
 *
 * Structure deliberately mirrors BoutiqueIdraulicaDemoSeeder rather than
 * sharing a base class: each client demo is edited on its own during a sales
 * cycle, and a shared parent turns every tweak into a change that can break
 * somebody else's live instance.
 */
final class IdroclimaRecaldiniDemoSeeder extends Seeder
{
    private const REGION = 'lombardia';

    public function run(): void
    {
        $today = CarbonImmutable::today();

        $team = $this->seedTeam();
        $statuses = $this->seedWorkOrderStatuses();
        $customers = $this->seedCustomers();

        $this->seedHeatingSystems($customers, $today);
        $this->seedRefrigerantCircuits($customers, $today);

        $boilerSwap = $this->seedBoilerSwap($customers['brambilla'], $team['technician'], $today);
        $heatPump = $this->seedHeatPumpJob($customers['colombo'], $team['technician'], $today);
        $condominium = $this->seedCondominiumJob($customers['giardini'], $team['technician'], $today);

        $boilerSwap->forceFill(['status_id' => $statuses['invoiced']])->save();
        $heatPump->forceFill(['status_id' => $statuses['invoiced']])->save();
        // Collaudato ma non fatturato: la riga che Controllo di gestione segnala.
        $condominium->forceFill(['status_id' => $statuses['commissioned']])->save();

        $this->seedCustomerInvoices($customers, $boilerSwap, $heatPump, $condominium, $today);
        $this->seedSupplierBills($today);
    }

    /**
     * @return array{owner: User, technician: User}
     */
    private function seedTeam(): array
    {
        $owner = $this->demoUser('titolare@idroclimarecaldini.demo', 'Luca Recaldini', 'admin');

        $technician = $this->demoUser('tecnico@idroclimarecaldini.demo', 'Stefano Meregalli', 'operator');
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

            $ids[$code] = (int) DB::table('work_order_statuses')->where('code', $code)->value('id');
        }

        return $ids;
    }

    /**
     * @return array<string, Customer>
     */
    private function seedCustomers(): array
    {
        $definitions = [
            'brambilla' => ['Brambilla Ernesto', 'Via Salvo D\'Acquisto 18', 'Pozzuolo Martesana', 'MI', '20060', '02 95357120'],
            'colombo' => ['Colombo Silvia', 'Via Trieste 41', 'Melzo', 'MI', '20066', '02 95738440'],
            'giardini' => ['Condominio I Giardini', 'Via Cavour 9', 'Gessate', 'MI', '20060', '02 95781230'],
            'ferrario' => ['Ferrario Giancarlo', 'Via Piave 6', 'Truccazzano', 'MI', '20060', '02 95309870'],
            'panetteria' => ['Panetteria Il Forno Snc', 'Via Roma 77', 'Cernusco sul Naviglio', 'MI', '20063', '02 92104550'],
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
     * Six systems, weighted towards trouble: two already past their date, two
     * inside the sixty-day window, two clear. A demo that opens on an all-green
     * list proves the screen works and nothing else.
     *
     * @param  array<string, Customer>  $customers
     */
    private function seedHeatingSystems(array $customers, CarbonImmutable $today): void
    {
        $definitions = [
            ['IMP-2026-011', 'brambilla', 'Caldaia murale villetta', 'Vaillant', 'ecoTEC plus VMW 306',
                '21203400778120', 30.0, $today->subMonths(26), $today->subDays(95)],
            ['IMP-2026-012', 'giardini', 'Centrale termica condominiale', 'Riello', 'Condens 160',
                'RL2022660914', 160.0, $today->subMonths(34), $today->subDays(41)],
            ['IMP-2026-013', 'colombo', 'Pompa di calore aria-acqua', 'Daikin', 'Altherma 3 H HT',
                'DK2025114780', 14.0, $today->subMonths(9), $today->addDays(22)],
            ['IMP-2026-014', 'ferrario', 'Caldaia a condensazione', 'Baxi', 'Luna Duo-tec MP+ 35',
                'BX2024903112', 35.0, $today->subMonths(15), $today->addDays(48)],
            ['IMP-2026-015', 'panetteria', 'Generatore laboratorio', 'Immergas', 'Victrix Pro 55',
                'IM2023448201', 55.0, $today->subMonths(29), $today->addMonths(5)],
            ['IMP-2026-016', 'brambilla', 'Caldaia dependance', 'Ariston', 'Genus One 24',
                'AR2025770044', 24.0, $today->subMonths(7), $today->addMonths(8)],
        ];

        foreach ($definitions as [$code, $customerKey, $description, $brand, $model, $serial, $powerKw, $installedOn, $nextMaintenance]) {
            $system = HeatingSystem::query()->firstOrCreate(
                ['code' => $code],
                [
                    'customer_id' => $customers[$customerKey]->id,
                    'description' => $description,
                    'fuel_type' => 'metano',
                    'total_power_kw' => $powerKw,
                    'plant_registry_code' => 'MI'.str_pad((string) random_int(1, 999999), 8, '0', STR_PAD_LEFT),
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
     * Air conditioning and heat pumps, which is where the F-Gas duty bites.
     *
     * @param  array<string, Customer>  $customers
     */
    private function seedRefrigerantCircuits(array $customers, CarbonImmutable $today): void
    {
        $obligations = new CalculateFGasObligationAction();

        $definitions = [
            // Sealed domestic split, under the threshold: no duty, and the page says why.
            ['FGAS-011', 'ferrario', 'Climatizzatore dualsplit soggiorno', 'R32', 1.8, true, false, $today->subMonths(9)],
            // Heat pump past 5 t CO2eq: annual check, last one 13 months ago.
            ['FGAS-012', 'colombo', 'Pompa di calore aria-acqua 14 kW', 'R410A', 3.9, false, false, $today->subMonths(13)],
            // Bakery cold room: into the six-month band.
            ['FGAS-013', 'panetteria', 'Cella frigo laboratorio', 'R404A', 16.0, false, false, $today->subMonths(5)],
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
                    'installation_date' => $lastCheck->subYears(3),
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
                    'operator_certificate' => 'FGAS-IT-2023-094411',
                    'next_due_on' => $obligation->nextCheckDueOn?->format('Y-m-d'),
                ],
            );
        }
    }

    /** The bread-and-butter job, priced and run the way it should be. */
    private function seedBoilerSwap(Customer $customer, User $technician, CarbonImmutable $today): WorkOrder
    {
        $workOrder = WorkOrder::query()->firstOrCreate(
            ['code' => 'CM-2026-221'],
            [
                'customer_id' => $customer->id,
                'title' => 'Sostituzione caldaia villetta',
                'description' => 'Rimozione generatore esistente, posa condensazione 30 kW, scarico condensa e collaudo.',
                'planned_minutes' => 600,
                'opened_at' => $today->subDays(52),
                'scheduled_at' => $today->subDays(46),
            ],
        );

        $intervention = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $today->subDays(46)->setTime(8, 0)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $today->subDays(46)->setTime(17, 0),
                'work_minutes' => 540,
                'amount' => 660.00,
                'callout_fee' => 45.00,
                'travel_km' => 9,
                'notes' => 'Sostituzione conclusa in giornata, collaudo e messa in servizio ok.',
            ],
        );

        $this->materialLine($intervention, 'Caldaia a condensazione 30 kW', 1, 'pz', 1690.00);
        $this->materialLine($intervention, 'Kit scarico condensa', 1, 'kit', 98.00);

        $this->deliveryNote($workOrder, 'DDT-2026-7710', $today->subDays(48), [
            ['Caldaia a condensazione 30 kW', 1, 1020.00],
            ['Kit scarico condensa', 1, 54.00],
        ]);

        $intervention->refreshEconomicTotals();

        return $workOrder;
    }

    /** The job that ate its margin: three site visits instead of one. */
    private function seedHeatPumpJob(Customer $customer, User $technician, CarbonImmutable $today): WorkOrder
    {
        $workOrder = WorkOrder::query()->firstOrCreate(
            ['code' => 'CM-2026-234'],
            [
                'customer_id' => $customer->id,
                'title' => 'Pompa di calore + rifacimento collettori',
                'description' => 'Posa pompa di calore aria-acqua 14 kW, sostituzione collettori e valvolame.',
                'planned_minutes' => 1200,
                'opened_at' => $today->subDays(38),
                'scheduled_at' => $today->subDays(30),
            ],
        );

        $visits = [
            [30, 8, 18, 30, 600, 700.00, 'Posa unità esterna e predisposizione idraulica.'],
            [26, 8, 19, 0, 660, 760.00, 'Trovato impianto radiante non compatibile: rifatti i collettori.'],
            [21, 8, 15, 30, 450, 0.00, 'Ritaratura e collaudo finale. Non fatturato al cliente.'],
        ];

        $interventions = [];

        foreach ($visits as $index => [$daysAgo, $startHour, $endHour, $endMinute, $minutes, $amount, $note]) {
            $interventions[$index] = Intervention::query()->firstOrCreate(
                ['work_order_id' => $workOrder->id, 'started_at' => $today->subDays($daysAgo)->setTime($startHour, 0)],
                [
                    'performed_by_user_id' => $technician->id,
                    'ended_at' => $today->subDays($daysAgo)->setTime($endHour, $endMinute),
                    'work_minutes' => $minutes,
                    'amount' => $amount,
                    'callout_fee' => 0,
                    'travel_km' => 14,
                    'notes' => $note,
                ],
            );
        }

        // La posa sta sulla prima visita, i collettori rifatti sulla seconda: e'
        // quella la riga che spiega dove se n'e' andato il margine.
        $firstVisit = $interventions[0] ?? null;
        $secondVisit = $interventions[1] ?? null;

        if ($firstVisit instanceof Intervention) {
            $this->materialLine($firstVisit, 'Pompa di calore aria-acqua 14 kW', 1, 'pz', 5400.00);
        }

        if ($secondVisit instanceof Intervention) {
            $this->materialLine($secondVisit, 'Collettore in ottone 10 vie', 2, 'pz', 184.00);
            $this->materialLine($secondVisit, 'Valvolame e raccorderia', 1, 'kit', 420.00);
        }

        $this->deliveryNote($workOrder, 'DDT-2026-7802', $today->subDays(31), [
            ['Pompa di calore aria-acqua 14 kW', 1, 4380.00],
        ]);

        // Il secondo DDT e' quello che ribalta il margine: materiale non previsto.
        $this->deliveryNote($workOrder, 'DDT-2026-7846', $today->subDays(27), [
            ['Collettore in ottone 10 vie', 2, 132.00],
            ['Valvolame e raccorderia', 1, 505.00],
            ['Isolante e minuteria', 1, 260.00],
        ]);

        foreach ($interventions as $intervention) {
            $intervention->refreshEconomicTotals();
        }

        return $workOrder;
    }

    /** Finished, commissioned, and sitting uninvoiced. */
    private function seedCondominiumJob(Customer $customer, User $technician, CarbonImmutable $today): WorkOrder
    {
        $workOrder = WorkOrder::query()->firstOrCreate(
            ['code' => 'CM-2026-240'],
            [
                'customer_id' => $customer->id,
                'title' => 'Manutenzione straordinaria centrale termica',
                'description' => 'Revisione bruciatore, sostituzione valvolame e pulizia scambiatore.',
                'planned_minutes' => 540,
                'opened_at' => $today->subDays(11),
                'scheduled_at' => $today->subDays(7),
            ],
        );

        $intervention = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $today->subDays(7)->setTime(8, 30)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $today->subDays(7)->setTime(16, 30),
                'work_minutes' => 480,
                'amount' => 0,
                'callout_fee' => 0,
                'travel_km' => 11,
                'notes' => 'Lavoro concluso e collaudato. Da fatturare all\'amministratore.',
            ],
        );

        $this->deliveryNote($workOrder, 'DDT-2026-7901', $today->subDays(8), [
            ['Valvolame centrale termica', 1, 470.00],
        ]);

        $intervention->refreshEconomicTotals();

        return $workOrder;
    }

    /**
     * @param  array<string, Customer>  $customers
     */
    private function seedCustomerInvoices(
        array $customers,
        WorkOrder $boilerSwap,
        WorkOrder $heatPump,
        WorkOrder $condominium,
        CarbonImmutable $today,
    ): void {
        $definitions = [
            ['FT/2026/0712', 'brambilla', $boilerSwap->id, $today->subDays(70), $today->subDays(40), 3180.00, 3180.00, $today->subDays(38)],
            ['FT/2026/0748', 'colombo', $heatPump->id, $today->subDays(55), $today->subDays(25), 8420.00, 3000.00, null],
            ['FT/2026/0655', 'giardini', null, $today->subDays(160), $today->subDays(130), 4260.00, 0.00, null],
            ['FT/2026/0690', 'panetteria', null, $today->subDays(112), $today->subDays(82), 1890.00, 0.00, null],
            ['FT/2026/0779', 'ferrario', $condominium->id, $today->subDays(3), $today->addDays(27), 1620.00, 0.00, null],
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

    private function seedSupplierBills(CarbonImmutable $today): void
    {
        $supplier = $this->supplier();

        $definitions = [
            ['FPA-2026-2210', $today->subDays(40), $today->addDays(6), 6180.00],
            ['FPA-2026-2247', $today->subDays(22), $today->addDays(19), 2740.00],
            ['FPA-2026-2288', $today->subDays(5), $today->addDays(51), 1460.00],
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

    private function supplier(): Supplier
    {
        return Supplier::query()->firstOrCreate(
            ['legal_name' => 'Termoidraulica Martesana Srl'],
            [
                'vat_number' => '05120470961',
                'email' => 'ordini@termomartesana.demo',
                'phone' => '02 95120400',
                'is_active' => true,
            ],
        );
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
        $note = DeliveryNote::query()->firstOrCreate(
            ['supplier_id' => $this->supplier()->id, 'number' => $number],
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
}
