<?php

declare(strict_types=1);

namespace Modules\Signage\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;
use Modules\WorkOrder\Actions\CreateWorkOrderFromQuotationAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;

/**
 * Demo data for the sign-shop flow, built on the same real figures as
 * {@see SignPriceListSeeder}: the customer, brand, sign codes and sizes come
 * from the MM BERLINO drawing used as the reference artwork, the unit costs are
 * the supplier ones (METALLI ITALIA, RETAIL SIGN, Blueview), and the quantities
 * follow the estimation rules (area, perimeter, minutes per letter).
 *
 * The three offers are deliberately different economically — a healthy markup,
 * a thin one, and one quoted below cost — so margin badges, the per-brand chart
 * and the overdue-delivery colours all have something real to show. Idempotent:
 * keyed on quotation code.
 */
final class SignageDemoSeeder extends Seeder
{
    /** The stages a sign shop actually goes through, in order. */
    /** @var array<int, array{code: string, name: string, color: string, is_terminal: bool, sort_order: int}> */
    private const WORKFLOW_STATUSES = [
        ['code' => 'quoting', 'name' => 'Preventivazione', 'color' => 'gray', 'is_terminal' => false, 'sort_order' => 10],
        ['code' => 'approved', 'name' => 'Approvata', 'color' => 'blue', 'is_terminal' => false, 'sort_order' => 20],
        ['code' => 'production', 'name' => 'In produzione', 'color' => 'amber', 'is_terminal' => false, 'sort_order' => 30],
        ['code' => 'shipping', 'name' => 'In spedizione', 'color' => 'blue', 'is_terminal' => false, 'sort_order' => 40],
        ['code' => 'closed', 'name' => 'Chiusa', 'color' => 'green', 'is_terminal' => true, 'sort_order' => 50],
    ];

    /** @var float */
    private const VAT_PERCENT = 22.0;

    public function run(): void
    {
        (new SignPriceListSeeder())->run();
        (new SignWorkCenterSeeder())->run();
        (new SignEstimationRuleSeeder())->run();
        (new SignCatalogDemoSeeder())->run();

        $this->seedWorkflowStatuses();

        // The company that orders the signs (from the drawing title block); the
        // brand on the sign is the project, not the customer.
        $customer = Customer::query()->updateOrCreate(
            ['legal_name' => 'ARREDO 91 PRODUZIONE SRL'],
            ['is_active' => true, 'city' => 'Reggio Emilia'],
        );

        foreach ($this->blueprints() as $blueprint) {
            $quotation = $this->seedQuotation($customer, $blueprint);

            if ($blueprint['work_order'] !== null) {
                $this->seedWorkOrder($quotation, $blueprint['work_order']);
            }
        }
    }

    private function seedWorkflowStatuses(): void
    {
        foreach (self::WORKFLOW_STATUSES as $status) {
            WorkOrderStatus::query()->updateOrCreate(['code' => $status['code']], $status);
        }
    }

    /**
     * Quantities are the ones the pricing rules would work out from the sign
     * geometry: sheet area = L×H×pieces, milling = perimeter×pieces, LED = 90%
     * of the perimeter, folding/soldering = 60 min per letter minimum.
     *
     * @return list<array{
     *     code: string, title: string, project: string, status: string, notes: string,
     *     markup: float, delivery: ?string, lead_time: ?int,
     *     items: list<array{description: string, quantity: float, unit_cost: float}>,
     *     work_order: ?array{status: string, progress: int, delivery: string}
     * }>
     */
    private function blueprints(): array
    {
        return [
            [
                // The reference drawing: 4 pieces, 800×900 mm, RAL 8019 matt, 3500K.
                'code' => 'PRE-INS-2026-001',
                'title' => 'MM_BLOCK25_8019_800900 — MM BERLINO podium signs (4 pz, 800×900)',
                'project' => 'MaxMara',
                'status' => 'confirmed',
                'notes' => "Rif. disegno A91 — MM BERLINO podium signs.\nVerniciatura RAL 8019 GREY BROWN, finitura MATT, illuminazione 3500K.\nNota disegno: PRODUCT NOT INSPECTIONABLE.",
                'markup' => 65.0,
                'delivery' => '+25 days',
                'lead_time' => null,
                'items' => [
                    // 0,80 × 0,90 × 4 pz = 2,88 mq
                    ['description' => 'Lastra ottone OT63 lucido a specchio + PVC / CRUDO - MM 2000x1000x1 [mq]', 'quantity' => 2.88, 'unit_cost' => 116.45],
                    // perimetro 2×(0,80+0,90) = 3,40 mt × 4 pz = 13,60 mt
                    ['description' => 'Taglio fresa ottone 1.0mm [mt]', 'quantity' => 13.60, 'unit_cost' => 2.10],
                    // 90% del perimetro = 12,24 mt
                    ['description' => 'STRIPLED MINI SERIES 2016 SMD 3500K 24V 9.36W/M [mt]', 'quantity' => 12.24, 'unit_cost' => 3.65],
                    // minimo 60 min per lettera × 4
                    ['description' => 'Piegatura + stagnatura lettere scatolate [min]', 'quantity' => 240.0, 'unit_cost' => 0.50],
                    ['description' => 'Elaborazione file Retail Sign [min]', 'quantity' => 90.0, 'unit_cost' => 0.70],
                ],
                'work_order' => ['status' => 'production', 'progress' => 60, 'delivery' => '+25 days'],
            ],
            [
                // Same family, smaller sign, thinner markup: the amber case.
                'code' => 'PRE-INS-2026-002',
                'title' => 'MM_BLOCK25_8019_600450 — MM MONACO vetrina (6 pz, 600×450)',
                'project' => 'Max&Co',
                'status' => 'sent',
                'notes' => "Lettere scatolate ottone 0,8 mm, finitura COTTO.\nRicarico ridotto per rinnovo listino annuale cliente.",
                'markup' => 12.0,
                'delivery' => null,
                'lead_time' => 20,
                'items' => [
                    // 0,60 × 0,45 × 6 pz = 1,62 mq
                    ['description' => 'Lastra ottone OT63 lucido a specchio + PVC / COTTO - MM 2000x1000x0,8 [mq]', 'quantity' => 1.62, 'unit_cost' => 93.16],
                    // perimetro 2×(0,60+0,45) = 2,10 mt × 6 pz = 12,60 mt
                    ['description' => 'Taglio fresa ottone 0.8mm [mt]', 'quantity' => 12.60, 'unit_cost' => 1.40],
                    ['description' => 'STRIPLED MINI SERIES 2016 SMD 3000K 24V 9.36W/M [mt]', 'quantity' => 11.34, 'unit_cost' => 3.65],
                    ['description' => 'Piegatura + stagnatura lettere scatolate [min]', 'quantity' => 360.0, 'unit_cost' => 0.50],
                ],
                // Offer sent, not won yet: no job order.
                'work_order' => null,
            ],
            [
                // Fixed price agreed before the costs were known: quoted below cost.
                'code' => 'PRE-INS-2026-003',
                'title' => 'MM_BLOCK25_8019_1200900 — MR DUOMO insegna ingresso (2 pz, 1200×900)',
                'project' => 'Marina Rinaldi',
                'status' => 'confirmed',
                'notes' => "Prezzo bloccato da accordo quadro firmato prima della revisione listino ottone.\nDa rinegoziare al prossimo ordine.",
                'markup' => -8.0,
                'delivery' => '-3 days',
                'lead_time' => null,
                'items' => [
                    // 1,20 × 0,90 × 2 pz = 2,16 mq
                    ['description' => 'Lastra ottone OT63 lucido a specchio + PVC / CRUDO - MM 2000x1000x1 [mq]', 'quantity' => 2.16, 'unit_cost' => 116.45],
                    // perimetro 2×(1,20+0,90) = 4,20 mt × 2 pz = 8,40 mt
                    ['description' => 'Taglio fresa ottone 1.0mm [mt]', 'quantity' => 8.40, 'unit_cost' => 2.10],
                    ['description' => 'STRIPLED MINI SERIES 2016 SMD 4000K 24V 9.36W/M [mt]', 'quantity' => 7.56, 'unit_cost' => 3.65],
                    ['description' => 'Piegatura + stagnatura lettere scatolate [min]', 'quantity' => 240.0, 'unit_cost' => 0.50],
                    ['description' => 'Elaborazione file Retail Sign [min]', 'quantity' => 120.0, 'unit_cost' => 0.70],
                ],
                'work_order' => ['status' => 'shipping', 'progress' => 90, 'delivery' => '-3 days'],
            ],
        ];
    }

    /**
     * @param  array{
     *     code: string, title: string, project: string, status: string, notes: string,
     *     markup: float, delivery: ?string, lead_time: ?int,
     *     items: list<array{description: string, quantity: float, unit_cost: float}>,
     *     work_order: ?array{status: string, progress: int, delivery: string}
     * }  $blueprint
     */
    private function seedQuotation(Customer $customer, array $blueprint): Quotation
    {
        /** @var Quotation $quotation */
        $quotation = Quotation::query()->updateOrCreate(
            ['code' => $blueprint['code']],
            [
                'customer_id' => $customer->id,
                'status_code' => $blueprint['status'],
                'title' => $blueprint['title'],
                'project' => $blueprint['project'],
                'notes' => $blueprint['notes'],
                'tax_percent' => self::VAT_PERCENT,
                'issued_at' => Carbon::parse('-10 days'),
                'confirmed_at' => $blueprint['status'] === 'confirmed' ? Carbon::parse('-6 days') : null,
                'delivery_at' => $blueprint['delivery'] !== null ? Carbon::parse($blueprint['delivery']) : null,
                'lead_time_days' => $blueprint['lead_time'],
            ],
        );

        $quotation->items()->delete();

        $costTotal = 0.0;
        $subtotal = 0.0;
        $sortOrder = 0;

        foreach ($blueprint['items'] as $item) {
            $unitPrice = round($item['unit_cost'] * (1 + $blueprint['markup'] / 100), 2);
            $lineTotal = round($item['quantity'] * $unitPrice, 2);

            $costTotal += $item['quantity'] * $item['unit_cost'];
            $subtotal += $lineTotal;

            QuotationItem::query()->create([
                'quotation_id' => $quotation->id,
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $unitPrice,
                'line_total' => $lineTotal,
                'sort_order' => $sortOrder++,
            ]);
        }

        $taxAmount = round($subtotal * self::VAT_PERCENT / 100, 2);

        $quotation->forceFill([
            'cost_total' => round($costTotal, 2),
            'subtotal' => round($subtotal, 2),
            'taxable_amount' => round($subtotal, 2),
            'tax_amount' => $taxAmount,
            'total' => round($subtotal + $taxAmount, 2),
        ])->save();

        return $quotation->refresh();
    }

    /**
     * @param  array{status: string, progress: int, delivery: string}  $blueprint
     */
    private function seedWorkOrder(Quotation $quotation, array $blueprint): void
    {
        $existing = $quotation->work_order_id !== null
            ? WorkOrder::query()->find($quotation->work_order_id)
            : null;

        $workOrder = $existing instanceof WorkOrder
            ? $existing
            : app(CreateWorkOrderFromQuotationAction::class)->execute($quotation, [
                'status_code' => $blueprint['status'],
                'delivery_due_at' => Carbon::parse($blueprint['delivery'])->toDateTimeString(),
            ]);

        $workOrder->forceFill([
            'progress_percent' => $blueprint['progress'],
            'delivery_due_at' => Carbon::parse($blueprint['delivery']),
            'started_at' => $workOrder->started_at ?? Carbon::parse('-5 days'),
        ])->save();
    }
}
