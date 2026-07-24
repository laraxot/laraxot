<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Actions;

use Modules\Customer\Models\Customer;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Spatie\QueueableAction\QueueableAction;

final class CreateContractFromOpportunityAction
{
    use QueueableAction;

    public function execute(PublicProcurementTenderOpportunity $opportunity): PublicProcurementContract
    {
        /** @var Customer $customer */
        $customer = $opportunity->customer instanceof Customer
            ? $opportunity->customer
            : Customer::query()->firstOrCreate(
                ['legal_name' => $opportunity->contracting_authority],
                ['is_active' => true],
            );

        /** @var WorkOrderStatus $status */
        $status = WorkOrderStatus::query()->firstOrCreate(
            ['code' => 'public_tender'],
            ['name' => 'Gara pubblica', 'color' => 'info', 'sort_order' => 15],
        );

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->firstOrCreate(
            ['code' => 'PA-'.($opportunity->cig ?: str_pad((string) $opportunity->id, 5, '0', STR_PAD_LEFT))],
            [
                'customer_id' => $customer->id,
                'status_id' => $status->id,
                'title' => $opportunity->title,
                'description' => $opportunity->raw_excerpt,
                'opened_at' => now(),
                'scheduled_at' => $opportunity->submission_deadline_at,
            ],
        );

        /** @var PublicProcurementContract $contract */
        $contract = PublicProcurementContract::query()->updateOrCreate(
            ['tender_opportunity_id' => $opportunity->id],
            [
                'work_order_id' => $workOrder->id,
                'customer_id' => $customer->id,
                'status' => 'tender_participation',
                'code' => 'APP-'.($opportunity->cig ?: str_pad((string) $opportunity->id, 5, '0', STR_PAD_LEFT)),
                'title' => $opportunity->title,
                'contracting_authority' => $opportunity->contracting_authority,
                'cig' => $opportunity->cig,
                'cup' => $opportunity->cup,
                'contract_amount' => $opportunity->estimated_amount ?? 0,
                'description' => $opportunity->raw_excerpt,
                'meta' => ['created_from_tender_opportunity_id' => $opportunity->id],
            ],
        );

        $opportunity->forceFill(['status' => 'participation_started', 'customer_id' => $customer->id])->save();

        return $contract;
    }
}
