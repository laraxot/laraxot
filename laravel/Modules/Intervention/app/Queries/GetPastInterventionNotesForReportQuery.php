<?php

declare(strict_types=1);

namespace Modules\Intervention\Queries;

use Illuminate\Database\Eloquent\Builder;
use Modules\Intervention\Models\Intervention;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * Returns the notes of the most recent validated interventions for the same
 * customer as the given one, used as few-shot examples so the AI matches the
 * customer's terminology and reporting style.
 */
final class GetPastInterventionNotesForReportQuery
{
    /**
     * @return list<array{notes: string}>
     */
    public function execute(Intervention $intervention, int $limit = 5): array
    {
        $customerId = $this->resolveCustomerId($intervention);
        if ($customerId === null) {
            return [];
        }

        $workOrderIds = WorkOrder::query()
            ->where('customer_id', $customerId)
            ->pluck('id')
            ->all();

        $notes = Intervention::query()
            ->whereKeyNot($intervention->getKey())
            ->whereNotNull('validated_at')
            ->where(function (Builder $query) use ($customerId, $workOrderIds): void {
                $query
                    ->where('customer_id', $customerId)
                    ->orWhereIn('work_order_id', $workOrderIds);
            })
            ->whereNotNull('notes')
            ->where('notes', '!=', '')
            ->orderByDesc('scheduled_at')
            ->orderByDesc('id')
            ->limit(max(1, $limit))
            ->pluck('notes')
            ->all();

        return array_values(array_map(
            static fn (mixed $note): array => ['notes' => is_string($note) ? trim($note) : ''],
            $notes,
        ));
    }

    private function resolveCustomerId(Intervention $intervention): ?int
    {
        $direct = $intervention->customer_id;
        if ($direct !== null && (int) $direct > 0) {
            return (int) $direct;
        }

        $viaWorkOrder = $intervention->workOrder?->customer_id;

        return $viaWorkOrder !== null && (int) $viaWorkOrder > 0 ? (int) $viaWorkOrder : null;
    }
}
