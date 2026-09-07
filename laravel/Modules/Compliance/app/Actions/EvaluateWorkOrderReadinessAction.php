<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Carbon\CarbonImmutable;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\Compliance\Models\WorkOrderReadinessItem;
use Modules\WorkOrder\Models\WorkOrder;
use Spatie\QueueableAction\QueueableAction;

final class EvaluateWorkOrderReadinessAction
{
    use QueueableAction;

    /** @var array<int, string> */
    private const array DEFAULT_REQUIRED_DOCUMENTS = ['durc', 'pos', 'dico'];

    /**
     * @return array{status: string, blocking: int, warnings: int, items: array<int, array<string, mixed>>}
     */
    public function execute(WorkOrder $workOrder): array
    {
        $items = [];

        foreach (self::DEFAULT_REQUIRED_DOCUMENTS as $type) {
            $document = $this->latestDocument($workOrder, $type);
            $items[] = $document instanceof ComplianceDocument
                ? $this->documentItem($type, $document)
                : $this->missingDocumentItem($type);
        }

        $pendingChangeOrders = WorkOrderChangeOrder::query()
            ->where('work_order_id', $workOrder->id)
            ->whereIn('status', ['proposed', 'pending_approval'])
            ->count();

        if ($pendingChangeOrders > 0) {
            $items[] = [
                'code' => 'pending_change_orders',
                'label' => 'Extra lavori non approvati',
                'severity' => 'warning',
                'status' => 'pending',
                'message' => $pendingChangeOrders.' extra lavori o varianti sono ancora da approvare.',
                'source_type' => null,
                'source_id' => null,
                'due_on' => null,
            ];
        }

        $this->persistItems($workOrder, $items);

        $blocking = collect($items)->where('severity', 'blocking')->where('status', '!=', 'ok')->count();
        $warnings = collect($items)->where('severity', 'warning')->where('status', '!=', 'ok')->count();

        return [
            'status' => $blocking > 0 ? 'red' : ($warnings > 0 ? 'yellow' : 'green'),
            'blocking' => $blocking,
            'warnings' => $warnings,
            'items' => $items,
        ];
    }

    private function latestDocument(WorkOrder $workOrder, string $type): ?ComplianceDocument
    {
        /** @var ComplianceDocument|null $document */
        $document = ComplianceDocument::query()
            ->where('work_order_id', $workOrder->id)
            ->where('type', $type)
            ->latest('id')
            ->first();

        return $document;
    }

    /** @return array<string, mixed> */
    private function missingDocumentItem(string $type): array
    {
        return [
            'code' => 'document_'.$type,
            'label' => strtoupper($type),
            'severity' => $type === 'dico' ? 'warning' : 'blocking',
            'status' => 'missing',
            'message' => 'Documento '.strtoupper($type).' mancante per la commessa.',
            'source_type' => null,
            'source_id' => null,
            'due_on' => null,
        ];
    }

    /** @return array<string, mixed> */
    private function documentItem(string $type, ComplianceDocument $document): array
    {
        $status = $document->computedStatus();
        $severity = match ($status) {
            'expired' => 'blocking',
            'expiring' => 'warning',
            default => 'info',
        };

        return [
            'code' => 'document_'.$type,
            'label' => strtoupper($type),
            'severity' => $severity,
            'status' => in_array($status, ['valid', 'waived'], true) ? 'ok' : $status,
            'message' => match ($status) {
                'expired' => 'Documento '.strtoupper($type).' scaduto.',
                'expiring' => 'Documento '.strtoupper($type).' in scadenza.',
                'waived' => 'Documento '.strtoupper($type).' marcato come non necessario.',
                default => 'Documento '.strtoupper($type).' presente.',
            },
            'source_type' => ComplianceDocument::class,
            'source_id' => (int) $document->id,
            'due_on' => $document->expires_on?->toDateString(),
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function persistItems(WorkOrder $workOrder, array $items): void
    {
        $seenCodes = [];

        foreach ($items as $item) {
            $code = is_string($item['code']) ? $item['code'] : '';
            $seenCodes[] = $code;

            $label = is_string($item['label']) ? $item['label'] : '';
            $severity = is_string($item['severity']) ? $item['severity'] : '';
            $status = is_string($item['status']) ? $item['status'] : '';
            $message = is_string($item['message']) ? $item['message'] : '';

            WorkOrderReadinessItem::query()->updateOrCreate(
                ['work_order_id' => $workOrder->id, 'code' => $code],
                [
                    'label' => $label,
                    'severity' => $severity,
                    'status' => $status,
                    'message' => $message,
                    'source_type' => $item['source_type'] ?? null,
                    'source_id' => $item['source_id'] ?? null,
                    'due_on' => isset($item['due_on']) && is_string($item['due_on']) ? CarbonImmutable::parse($item['due_on'])->toDateString() : null,
                    'meta' => ['evaluated_at' => now()->toISOString()],
                ],
            );
        }

        WorkOrderReadinessItem::query()
            ->where('work_order_id', $workOrder->id)
            ->whereNotIn('code', $seenCodes)
            ->delete();
    }
}
