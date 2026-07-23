<?php

declare(strict_types=1);

namespace Modules\Platform\Database\Seeders;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Billing\Actions\ReconcileSupplierInvoiceWithDeliveryNotesAction;
use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\DeliveryNoteLine;
use Modules\Billing\Models\Supplier;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Billing\Models\SupplierInvoiceLine;
use Modules\Customer\Models\Customer;
use Modules\Document\Models\Media;
use Modules\Intervention\Actions\SyncWorkOrderExtraMaterialsAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;
use Modules\Quotation\Models\SiteSurvey;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * Demo per Botti Termoidraulica: una storia completa e coerente, non righe sparse.
 *
 * Sopralluogo con foto e nota vocale -> preventivo -> commessa con tempi previsti
 * -> due interventi con materiale, di cui una parte fuori preventivo -> extra da
 * decidere prima di fatturare -> fornitore con DDT e fattura che non torna.
 *
 * Tutti i dati sono fittizi. Il seeder e' idempotente: rilanciarlo non duplica.
 */
final class BottiTermoidraulicaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $now = CarbonImmutable::parse('2026-07-21 09:00:00');

        $team = $this->seedTeam();
        $customer = $this->seedCustomer();
        $survey = $this->seedSiteSurvey($customer, $team['technician'], $now);
        $workOrder = $this->seedWorkOrder($customer, $now);
        $quotation = $this->seedQuotation($customer, $workOrder, $now);

        $survey->forceFill([
            'work_order_id' => $workOrder->id,
            'quotation_id' => $quotation->id,
            'status_code' => 'quoted',
        ])->save();

        $this->seedInterventions($workOrder, $team['technician'], $now);
        $this->seedSupplierCycle($workOrder, $now);
    }

    /**
     * @return array{manager: User, technician: User}
     */
    private function seedTeam(): array
    {
        $manager = User::query()->firstOrCreate(
            ['email' => 'graziano.botti@demo.local'],
            ['name' => 'Graziano Botti', 'password' => Hash::make('password')],
        );

        // Gerarchia: il tecnico risponde al titolare, che cosi' vede il suo lavoro
        // senza bisogno di permessi da amministratore.
        $technician = User::query()->firstOrCreate(
            ['email' => 'marco.tecnico@demo.local'],
            ['name' => 'Marco Rossi', 'password' => Hash::make('password')],
        );
        $technician->forceFill(['manager_id' => $manager->id])->save();

        $apprentice = User::query()->firstOrCreate(
            ['email' => 'luca.apprendista@demo.local'],
            ['name' => 'Luca Bianchi', 'password' => Hash::make('password')],
        );
        $apprentice->forceFill(['manager_id' => $technician->id])->save();

        return ['manager' => $manager, 'technician' => $technician];
    }

    private function seedCustomer(): Customer
    {
        return Customer::query()->firstOrCreate(
            ['legal_name' => 'Condominio Le Terrazze'],
            [
                'vat_number' => '02938475610',
                'email' => 'amministratore@leterrazze.demo',
                'phone' => '0423 555120',
                'address_line1' => 'Via Molinetto 23',
                'city' => 'Borso del Grappa',
                'province' => 'TV',
                'postal_code' => '31030',
                'country_code' => 'IT',
                'is_active' => true,
            ],
        );
    }

    private function seedSiteSurvey(Customer $customer, User $technician, CarbonImmutable $now): SiteSurvey
    {
        $survey = SiteSurvey::query()->firstOrCreate(
            ['code' => 'SOP-2026-014'],
            [
                'customer_id' => $customer->id,
                'performed_by_user_id' => $technician->id,
                'title' => 'Sostituzione centrale termica',
                'notes' => 'Centrale in vano interrato, accesso stretto: serve caldaia a condensazione compatta. '
                    .'Canna fumaria da intubare. Contatore gas a 6 metri.',
                'status_code' => 'done',
                'scheduled_at' => $now->subDays(20),
                'performed_at' => $now->subDays(20)->setTime(9, 30),
            ],
        );

        // Gli allegati sono la sostanza del sopralluogo: senza, resta una riga di agenda.
        $attachments = [
            ['kind' => 'photo', 'title' => 'Centrale termica esistente', 'path' => 'demo/sopralluoghi/SOP-2026-014/centrale.jpg'],
            ['kind' => 'photo', 'title' => 'Canna fumaria', 'path' => 'demo/sopralluoghi/SOP-2026-014/canna-fumaria.jpg'],
            ['kind' => 'video', 'title' => 'Percorso tubazioni', 'path' => 'demo/sopralluoghi/SOP-2026-014/percorso.mp4'],
            ['kind' => 'audio', 'title' => 'Nota vocale del tecnico', 'path' => 'demo/sopralluoghi/SOP-2026-014/nota-vocale.m4a'],
        ];

        foreach ($attachments as $attachment) {
            Media::query()->firstOrCreate(
                ['path' => $attachment['path']],
                [
                    'mediable_type' => $survey->getMorphClass(),
                    'mediable_id' => $survey->id,
                    'uploaded_by_user_id' => $technician->id,
                    'kind' => $attachment['kind'],
                    'title' => $attachment['title'],
                    'disk' => 'public',
                ],
            );
        }

        return $survey;
    }

    private function seedWorkOrder(Customer $customer, CarbonImmutable $now): WorkOrder
    {
        return WorkOrder::query()->firstOrCreate(
            ['code' => 'CM-2026-047'],
            [
                'customer_id' => $customer->id,
                'title' => 'Sostituzione centrale termica condominiale',
                'description' => 'Smantellamento vecchia caldaia, posa caldaia a condensazione, intubamento canna fumaria.',
                // Previsto: 2 giornate e mezza di lavoro.
                'planned_minutes' => 1200,
                'opened_at' => $now->subDays(15),
                'scheduled_at' => $now->subDays(10),
            ],
        );
    }

    private function seedQuotation(Customer $customer, WorkOrder $workOrder, CarbonImmutable $now): Quotation
    {
        $quotation = Quotation::query()->firstOrCreate(
            ['code' => 'PRV-2026-091'],
            [
                'customer_id' => $customer->id,
                'work_order_id' => $workOrder->id,
                'title' => 'Sostituzione centrale termica',
                'status_code' => 'confirmed',
                'issued_at' => $now->subDays(18),
                'confirmed_at' => $now->subDays(15),
                'tax_percent' => 22,
            ],
        );

        $items = [
            ['description' => 'Caldaia a condensazione 35 kW', 'quantity' => 1, 'unit_price' => 2450.00],
            ['description' => 'Kit intubamento canna fumaria', 'quantity' => 1, 'unit_price' => 780.00],
            ['description' => 'Tubo multistrato 26mm', 'quantity' => 40, 'unit_price' => 6.50],
            ['description' => 'Valvola di zona', 'quantity' => 4, 'unit_price' => 62.00],
            ['description' => 'Manodopera installazione', 'quantity' => 20, 'unit_price' => 38.00],
        ];

        foreach ($items as $index => $item) {
            QuotationItem::query()->firstOrCreate(
                ['quotation_id' => $quotation->id, 'description' => $item['description']],
                [
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => round($item['quantity'] * $item['unit_price'], 2),
                    'sort_order' => $index,
                ],
            );
        }

        $subtotal = (float) QuotationItem::query()->where('quotation_id', $quotation->id)->sum('line_total');
        $quotation->forceFill([
            'subtotal' => $subtotal,
            'taxable_amount' => $subtotal,
            'tax_amount' => round($subtotal * 0.22, 2),
            'total' => round($subtotal * 1.22, 2),
        ])->save();

        return $quotation;
    }

    private function seedInterventions(WorkOrder $workOrder, User $technician, CarbonImmutable $now): void
    {
        // Primo intervento: tutto dentro il preventivo.
        $first = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $now->subDays(10)->setTime(8, 0)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $now->subDays(10)->setTime(17, 0),
                'work_minutes' => 480,
                'amount' => 304.00,
                'callout_fee' => 0,
                'travel_km' => 18,
                'notes' => 'Smantellamento vecchia caldaia e predisposizione attacchi.',
            ],
        );

        $this->materialLine($first, 'Tubo multistrato 26mm', 40, 'm', 6.50);
        $this->materialLine($first, 'Valvola di zona', 4, 'pz', 62.00);

        // Secondo intervento: qui nasce l'extra preventivo. In cantiere sono servite
        // 2 valvole in piu' e materiale mai preventivato: e' quello che oggi si regala.
        $second = Intervention::query()->firstOrCreate(
            ['work_order_id' => $workOrder->id, 'started_at' => $now->subDays(8)->setTime(8, 0)],
            [
                'performed_by_user_id' => $technician->id,
                'ended_at' => $now->subDays(8)->setTime(18, 30),
                'work_minutes' => 570,
                'amount' => 361.00,
                'callout_fee' => 45.00,
                'travel_km' => 18,
                'notes' => 'Posa caldaia e intubamento. Trovata canna fumaria ammalorata, '
                    .'aggiunte 2 valvole e staffaggi non previsti.',
            ],
        );

        $this->materialLine($second, 'Caldaia a condensazione 35 kW', 1, 'pz', 2450.00);
        $this->materialLine($second, 'Kit intubamento canna fumaria', 1, 'pz', 780.00);
        // Preventivate 4 valvole in totale, in cantiere ne servono 6: 2 sono extra.
        $this->materialLine($second, 'Valvola di zona', 2, 'pz', 62.00);
        // Mai preventivati: extra pieno.
        $this->materialLine($second, 'Staffaggio antivibrante', 6, 'pz', 14.50);
        $this->materialLine($second, 'Guarnizioni e minuteria', 1, 'kit', 38.00);

        // Il ricalcolo e' sulla commessa: il preventivo copre il totale, non ogni uscita.
        app(SyncWorkOrderExtraMaterialsAction::class)->execute($workOrder);

        // Una decisione gia' presa, per mostrare i tre stati possibili: le valvole
        // extra si fatturano, la minuteria si regala, il resto resta da decidere.
        InterventionMaterialLine::query()
            ->where('intervention_id', $second->id)
            ->where('description', 'Valvola di zona')
            ->update(['extra_status_code' => 'to_bill']);

        InterventionMaterialLine::query()
            ->where('intervention_id', $second->id)
            ->where('description', 'Guarnizioni e minuteria')
            ->update(['extra_status_code' => 'waived']);

        $first->refreshEconomicTotals();
        $second->refreshEconomicTotals();
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

    private function seedSupplierCycle(WorkOrder $workOrder, CarbonImmutable $now): void
    {
        $supplier = Supplier::query()->firstOrCreate(
            ['legal_name' => 'Termoidraulica Forniture Nord Srl'],
            [
                'vat_number' => '01827364590',
                'email' => 'ordini@fornitorenord.demo',
                'phone' => '0423 887700',
                'is_active' => true,
            ],
        );

        $note = DeliveryNote::query()->firstOrCreate(
            ['supplier_id' => $supplier->id, 'number' => 'DDT-2026-3391'],
            [
                'work_order_id' => $workOrder->id,
                'received_on' => $now->subDays(11)->toDateString(),
                'notes' => 'Consegna in cantiere.',
            ],
        );

        $deliveredLines = [
            ['description' => 'Caldaia a condensazione 35 kW', 'quantity' => 1, 'unit_price' => 1980.00],
            ['description' => 'Kit intubamento canna fumaria', 'quantity' => 1, 'unit_price' => 610.00],
            ['description' => 'Tubo multistrato 26mm', 'quantity' => 40, 'unit_price' => 4.20],
            ['description' => 'Valvola di zona', 'quantity' => 6, 'unit_price' => 44.00],
        ];

        foreach ($deliveredLines as $line) {
            DeliveryNoteLine::query()->firstOrCreate(
                ['delivery_note_id' => $note->id, 'description' => $line['description']],
                ['quantity' => $line['quantity'], 'unit_price' => $line['unit_price']],
            );
        }

        $invoice = SupplierInvoice::query()->firstOrCreate(
            ['supplier_id' => $supplier->id, 'number' => 'FT-2026-8842'],
            [
                'issued_on' => $now->subDays(4)->toDateString(),
                'notes' => 'Fattura relativa al DDT-2026-3391.',
            ],
        );

        // La fattura non torna, di proposito: sul tubo il fornitore fattura 45 metri
        // invece dei 40 consegnati, e la valvola a 48 euro invece di 44. Sono
        // esattamente gli errori che senza conciliazione si pagano senza accorgersene.
        $invoicedLines = [
            ['description' => 'Caldaia a condensazione 35 kW', 'quantity' => 1, 'unit_price' => 1980.00],
            ['description' => 'Kit intubamento canna fumaria', 'quantity' => 1, 'unit_price' => 610.00],
            ['description' => 'Tubo multistrato 26mm', 'quantity' => 45, 'unit_price' => 4.20],
            ['description' => 'Valvola di zona', 'quantity' => 6, 'unit_price' => 48.00],
        ];

        $total = 0.0;
        foreach ($invoicedLines as $line) {
            SupplierInvoiceLine::query()->firstOrCreate(
                ['supplier_invoice_id' => $invoice->id, 'description' => $line['description']],
                ['quantity' => $line['quantity'], 'unit_price' => $line['unit_price']],
            );
            $total += $line['quantity'] * $line['unit_price'];
        }

        $invoice->forceFill(['total' => round($total, 2)])->save();

        app(ReconcileSupplierInvoiceWithDeliveryNotesAction::class)->execute($invoice);
    }
}
