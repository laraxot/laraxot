<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\Catalog\Models\Product;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Platform\Settings\SettingsRepository;
use Modules\Quotation\Models\Quotation;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Spatie\QueueableAction\QueueableAction;

/**
 * Aggregates everything that happened in a date range: interventions grouped by
 * customer and by work order, materials consumed, quotations issued.
 *
 * The per-intervention amounts follow the same rule as the single-customer
 * report (see {@see GenerateCustomerReportAction}): the stored grand total wins
 * when present, otherwise labour + callout + materials are summed, and the
 * materials figure is the largest of the three sources that may carry it.
 */
final class BuildPeriodicSummaryAction
{
    use QueueableAction;

    /**
     * @return array{
     *     from: CarbonImmutable,
     *     to: CarbonImmutable,
     *     totals: array{
     *         customers: int, work_orders: int, work_orders_opened: int, work_orders_closed: int,
     *         interventions: int, minutes: int, labor: float, callout: float, materials: float, total: float
     *     },
     *     outcomes: array<string, int>,
     *     customers: list<array{name: string, interventions: int, minutes: int, labor: float, callout: float, materials: float, total: float}>,
     *     work_orders: list<array{code: string, title: string, customer: string, status: string, interventions: int, minutes: int, total: float}>,
     *     materials: list<array{label: string, source: 'warehouse'|'manual', quantity: float, amount: float}>,
     *     materials_total: float,
     *     quotations: array{rows: list<array{status: string, count: int, total: float}>, count: int, total: float}
     * }
     */
    public function execute(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $materialsEnabled = app(SettingsRepository::class)->getBool('inventory.allow_link_interventions', true);

        $interventions = Intervention::query()
            ->with([
                'workOrder.customer',
                'workOrder.status',
                'customer',
                'materialLines',
                'inventoryMovements' => fn (Relation $query) => $materialsEnabled
                    ? $query->where('movement_type', 'scarico')->with('product')
                    : $query->whereRaw('1=0'),
            ])
            ->whereBetween('scheduled_at', [$from, $to])
            ->orderBy('scheduled_at')
            ->get();

        $totals = [
            'customers' => 0,
            'work_orders' => 0,
            'work_orders_opened' => WorkOrder::query()->whereBetween('opened_at', [$from, $to])->count(),
            'work_orders_closed' => WorkOrder::query()->whereBetween('closed_at', [$from, $to])->count(),
            'interventions' => $interventions->count(),
            'minutes' => 0,
            'labor' => 0.0,
            'callout' => 0.0,
            'materials' => 0.0,
            'total' => 0.0,
        ];

        /** @var array<string, array{name: string, interventions: int, minutes: int, labor: float, callout: float, materials: float, total: float}> $customers */
        $customers = [];
        /** @var array<string, array{code: string, title: string, customer: string, status: string, interventions: int, minutes: int, total: float}> $workOrders */
        $workOrders = [];
        /** @var array<string, array{label: string, source: 'warehouse'|'manual', quantity: float, amount: float}> $materials */
        $materials = [];
        /** @var array<string, int> $outcomes */
        $outcomes = [];

        foreach ($interventions as $intervention) {
            $labor = $this->toFloat($intervention->amount);
            $callout = $this->toFloat($intervention->callout_fee);
            $materialsAmount = $materialsEnabled ? $this->materialsAmountFor($intervention) : 0.0;
            $stored = $this->toFloat($intervention->total_amount);
            $total = $stored > 0.0 ? $stored : $labor + $callout + $materialsAmount;
            $minutes = (int) ($intervention->work_minutes ?? 0);

            $totals['minutes'] += $minutes;
            $totals['labor'] += $labor;
            $totals['callout'] += $callout;
            $totals['materials'] += $materialsAmount;
            $totals['total'] += $total;

            $outcomeCode = (string) ($intervention->outcome_code ?? '');
            $outcomeKey = $outcomeCode !== '' ? $outcomeCode : 'unknown';
            $outcomes[$outcomeKey] = ($outcomes[$outcomeKey] ?? 0) + 1;

            // Relations are annotated explicitly: an intervention may hang off a
            // work order, off a customer directly, or off neither.
            /** @var WorkOrder|null $workOrder */
            $workOrder = $intervention->workOrder;
            /** @var Customer|null $customer */
            $customer = $workOrder !== null ? $workOrder->customer : $intervention->customer;
            $customerKey = $customer !== null ? (string) $customer->id : 'none';
            $customers[$customerKey] ??= [
                'name' => $customer !== null ? (string) $customer->legal_name : '',
                'interventions' => 0,
                'minutes' => 0,
                'labor' => 0.0,
                'callout' => 0.0,
                'materials' => 0.0,
                'total' => 0.0,
            ];
            $customers[$customerKey]['interventions']++;
            $customers[$customerKey]['minutes'] += $minutes;
            $customers[$customerKey]['labor'] += $labor;
            $customers[$customerKey]['callout'] += $callout;
            $customers[$customerKey]['materials'] += $materialsAmount;
            $customers[$customerKey]['total'] += $total;

            if ($workOrder !== null) {
                /** @var WorkOrderStatus|null $status */
                $status = $workOrder->status;
                $workOrderKey = (string) $workOrder->id;
                $workOrders[$workOrderKey] ??= [
                    'code' => (string) ($workOrder->code ?? ''),
                    'title' => (string) $workOrder->title,
                    'customer' => $customer !== null ? (string) $customer->legal_name : '',
                    'status' => $status !== null ? (string) $status->name : '',
                    'interventions' => 0,
                    'minutes' => 0,
                    'total' => 0.0,
                ];
                $workOrders[$workOrderKey]['interventions']++;
                $workOrders[$workOrderKey]['minutes'] += $minutes;
                $workOrders[$workOrderKey]['total'] += $total;
            }

            foreach ($intervention->inventoryMovements as $movement) {
                /** @var Product|null $product */
                $product = $movement->product;
                $this->addMaterial(
                    $materials,
                    $product !== null ? (string) $product->name : '',
                    'warehouse',
                    $this->toFloat($movement->quantity),
                    $this->toFloat($movement->line_total),
                );
            }

            foreach ($intervention->materialLines as $line) {
                $this->addMaterial(
                    $materials,
                    (string) $line->description,
                    'manual',
                    $this->toFloat($line->quantity),
                    $this->toFloat($line->line_total),
                );
            }
        }

        $totals['customers'] = count($customers);
        $totals['work_orders'] = count($workOrders);

        $customerRows = array_values($customers);
        usort($customerRows, static fn (array $a, array $b): int => $b['total'] <=> $a['total']);

        $workOrderRows = array_values($workOrders);
        usort($workOrderRows, static fn (array $a, array $b): int => $b['total'] <=> $a['total']);

        $materialRows = array_values($materials);
        usort($materialRows, static fn (array $a, array $b): int => $b['amount'] <=> $a['amount']);

        return [
            'from' => $from,
            'to' => $to,
            'totals' => $totals,
            'outcomes' => $outcomes,
            'customers' => $customerRows,
            'work_orders' => $workOrderRows,
            'materials' => $materialRows,
            'materials_total' => (float) array_sum(array_column($materialRows, 'amount')),
            'quotations' => $this->quotations($from, $to),
        ];
    }

    /**
     * @param  array<string, array{label: string, source: 'warehouse'|'manual', quantity: float, amount: float}>  $materials
     * @param  'warehouse'|'manual'  $source
     */
    private function addMaterial(array &$materials, string $label, string $source, float $quantity, float $amount): void
    {
        $key = $source.'|'.$label;
        $materials[$key] ??= [
            'label' => $label,
            'source' => $source,
            'quantity' => 0.0,
            'amount' => 0.0,
        ];
        $materials[$key]['quantity'] += $quantity;
        $materials[$key]['amount'] += $amount;
    }

    private function materialsAmountFor(Intervention $intervention): float
    {
        $fromMovements = $intervention->inventoryMovements
            ->sum(fn (InventoryMovement $movement): float => $this->toFloat($movement->line_total));
        $fromLines = $intervention->materialLines
            ->sum(fn (InterventionMaterialLine $line): float => $this->toFloat($line->line_total));

        return max(
            $this->toFloat($fromMovements),
            $this->toFloat($fromLines),
            $this->toFloat($intervention->materials_total),
        );
    }

    /**
     * @return array{rows: list<array{status: string, count: int, total: float}>, count: int, total: float}
     */
    private function quotations(CarbonImmutable $from, CarbonImmutable $to): array
    {
        // Quotations are dated by issue; drafts never issued fall back to their
        // creation date so nothing prepared in the period goes unreported.
        $quotations = Quotation::query()
            ->where(static function ($query) use ($from, $to): void {
                $query->whereBetween('issued_at', [$from, $to])
                    ->orWhere(static function ($fallback) use ($from, $to): void {
                        $fallback->whereNull('issued_at')->whereBetween('created_at', [$from, $to]);
                    });
            })
            ->get();

        /** @var array<string, array{status: string, count: int, total: float}> $rows */
        $rows = [];
        $total = 0.0;

        foreach ($quotations as $quotation) {
            $statusCode = (string) $quotation->status_code;
            $amount = $this->toFloat($quotation->total);
            $total += $amount;

            $rows[$statusCode] ??= ['status' => $statusCode, 'count' => 0, 'total' => 0.0];
            $rows[$statusCode]['count']++;
            $rows[$statusCode]['total'] += $amount;
        }

        $statusRows = array_values($rows);
        usort($statusRows, static fn (array $a, array $b): int => $b['total'] <=> $a['total']);

        return [
            'rows' => $statusRows,
            'count' => $quotations->count(),
            'total' => $total,
        ];
    }

    private function toFloat(mixed $value): float
    {
        return is_numeric($value) ? (float) $value : 0.0;
    }
}
