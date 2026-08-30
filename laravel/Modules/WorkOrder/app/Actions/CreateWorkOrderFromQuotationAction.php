<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderMaterial;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Spatie\QueueableAction\QueueableAction;

/**
 * Turns an approved quotation into the job order that delivers it, carrying
 * over what production actually needs: the quoted lines as the bill of
 * materials, the promised delivery date, and the link back to the quotation.
 *
 * Without the lines the job order is an empty shell and the workshop goes back
 * to reading the PDF of the offer.
 */
final class CreateWorkOrderFromQuotationAction
{
    use QueueableAction;

    /**
     * @param  array{title?: string, scheduled_at?: mixed, delivery_due_at?: mixed, status_code?: string}  $overrides
     */
    public function execute(Quotation $quotation, array $overrides = []): WorkOrder
    {
        return DB::transaction(function () use ($quotation, $overrides): WorkOrder {
            $workOrder = app(CreateWorkOrderAction::class)->execute([
                'code' => null,
                'customer_id' => (int) $quotation->customer_id,
                'customer_site_id' => null,
                'primary_contact_id' => null,
                'status_id' => $this->statusId($overrides['status_code'] ?? null),
                'title' => $this->title($quotation, $overrides),
                'description' => $quotation->project,
                'notes' => $quotation->notes,
                'opened_at' => now(),
                'scheduled_at' => $overrides['scheduled_at'] ?? null,
                'delivery_due_at' => $this->deliveryDueAt($quotation, $overrides),
            ]);

            $this->copyMaterials($quotation, $workOrder);

            $workOrderId = (int) $workOrder->id;
            $quotation->work_order_id = $workOrderId > 0 ? $workOrderId : null;
            $quotation->save();

            return $workOrder->refresh();
        });
    }

    private function copyMaterials(Quotation $quotation, WorkOrder $workOrder): void
    {
        $sortOrder = 0;

        /** @var QuotationItem $item */
        foreach ($quotation->items()->orderBy('sort_order')->orderBy('id')->get() as $item) {
            $quantity = (float) $item->quantity;
            $unitPrice = (float) $item->unit_price;

            WorkOrderMaterial::query()->create([
                'work_order_id' => $workOrder->id,
                'product_id' => $item->product_id,
                'description' => (string) $item->description,
                'quantity' => $quantity,
                'unit_cost' => $unitPrice,
                'line_total' => round($quantity * $unitPrice, 2),
                'source_code' => 'quotation',
                'sort_order' => $sortOrder++,
            ]);
        }
    }

    /**
     * @param  array{title?: string, scheduled_at?: mixed, delivery_due_at?: mixed, status_code?: string}  $overrides
     */
    private function title(Quotation $quotation, array $overrides): string
    {
        $title = $overrides['title'] ?? null;

        if (is_string($title) && $title !== '') {
            return $title;
        }

        $fromQuotation = $quotation->title;

        if (is_string($fromQuotation) && $fromQuotation !== '') {
            return $fromQuotation;
        }

        return 'Da preventivo '.($quotation->code !== null && $quotation->code !== ''
            ? $quotation->code
            : '#'.$quotation->id);
    }

    /**
     * @param  array{title?: string, scheduled_at?: mixed, delivery_due_at?: mixed, status_code?: string}  $overrides
     */
    private function deliveryDueAt(Quotation $quotation, array $overrides): ?CarbonImmutable
    {
        $explicit = $overrides['delivery_due_at'] ?? null;

        if (is_string($explicit) && $explicit !== '') {
            return CarbonImmutable::parse($explicit);
        }

        if ($explicit instanceof \DateTimeInterface) {
            return CarbonImmutable::instance($explicit);
        }

        if ($quotation->delivery_at !== null) {
            return CarbonImmutable::parse((string) $quotation->delivery_at);
        }

        // No promised date, but agreed lead time: count it from today, which is
        // when work actually starts.
        return $quotation->lead_time_days !== null
            ? CarbonImmutable::now()->addDays((int) $quotation->lead_time_days)
            : null;
    }

    private function statusId(?string $statusCode): ?int
    {
        if ($statusCode === null || $statusCode === '') {
            return null;
        }

        $id = WorkOrderStatus::query()->where('code', $statusCode)->value('id');

        return is_numeric($id) ? (int) $id : null;
    }
}
