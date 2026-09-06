<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Gate;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiToolLog;
use Modules\Compliance\Actions\EvaluateWorkOrderReadinessAction;
use Modules\Compliance\Actions\GeneratePosDraftForWorkOrderAction;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\Email\Models\EmailMessage;
use Modules\EnergyBroker\Actions\ConfirmEnergyBrokerDocumentImportAction;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\Intervention\Actions\CreateInterventionAction;
use Modules\Intervention\Actions\FinalizeInterventionReportAction;
use Modules\Intervention\Actions\StartTimePunchAction;
use Modules\Intervention\Actions\StopTimePunchAction;
use Modules\Intervention\Actions\UpsertDailyNoteForUserAction;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Actions\CreateInventoryMovementAction;
use Modules\Inventory\Actions\GetStockSnapshotAction;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Platform\Settings\SettingsRepository;
use Modules\Production\Actions\AdvanceProductionPhaseAction;
use Modules\Production\Actions\CreateProductionOrderAction;
use Modules\Production\Models\ProductionOrder;
use Modules\Production\Models\ProductionPhase;
use Modules\PublicProcurement\Actions\ApplyTenderOpportunityDraftAction;
use Modules\PublicProcurement\Actions\CreateContractFromOpportunityAction;
use Modules\PublicProcurement\Models\PublicProcurementAiExtraction;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementPriceItem;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\WhatsApp\Actions\OrganizeWhatsAppRequestAction;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusByCodeAction;
use Modules\WorkOrder\Models\WorkOrder;
use Spatie\QueueableAction\QueueableAction;
use Throwable;

final class ExecuteAiActionProposalAction
{
    use QueueableAction;

    /**
     * @return array<string, mixed>
     */
    public function execute(AiActionProposal $proposal, int|string $confirmedByUserId): array
    {
        $type = (string) $proposal->type;
        $payload = $proposal->payload;
        $payload = is_array($payload) ? $payload : null;

        $proposal->forceFill([
            'status' => 'confirmed',
            'confirmed_by_user_id' => $confirmedByUserId,
            'confirmed_at' => now(),
        ])->save();

        try {
            $result = match ($type) {
                'start_time_punch' => $this->executeStartTimePunch($proposal, $confirmedByUserId),
                'stop_time_punch' => $this->executeStopTimePunch($proposal, $confirmedByUserId),
                'upsert_daily_note' => $this->executeUpsertDailyNote($proposal, $confirmedByUserId),
                'change_work_order_status' => $this->executeChangeWorkOrderStatus($proposal, $confirmedByUserId),
                'finalize_intervention_report' => $this->executeFinalizeInterventionReport($proposal, $confirmedByUserId),
                'create_inventory_movement' => $this->executeCreateInventoryMovement($proposal, $confirmedByUserId),
                'allocate_inventory_to_work_order' => $this->executeAllocateInventoryToWorkOrder($proposal, $confirmedByUserId),
                'allocate_inventory_to_intervention' => $this->executeAllocateInventoryToIntervention($proposal, $confirmedByUserId),
                'create_production_order' => $this->executeCreateProductionOrder($proposal, $confirmedByUserId),
                'advance_production_phase' => $this->executeAdvanceProductionPhase($proposal, $confirmedByUserId),
                'create_compliance_document' => $this->executeCreateComplianceDocument($proposal, $confirmedByUserId),
                'create_work_order_change_order' => $this->executeCreateWorkOrderChangeOrder($proposal, $confirmedByUserId),
                'generate_pos_draft' => $this->executeGeneratePosDraft($proposal, $confirmedByUserId),
                'apply_public_procurement_tender_draft' => $this->executeApplyPublicProcurementTenderDraft($proposal, $confirmedByUserId),
                'create_public_procurement_contract_from_opportunity' => $this->executeCreatePublicProcurementContractFromOpportunity($proposal, $confirmedByUserId),
                'import_public_procurement_price_items' => $this->executeImportPublicProcurementPriceItems($proposal, $confirmedByUserId),
                'confirm_energy_broker_document_import' => $this->executeConfirmEnergyBrokerDocumentImport($proposal, $confirmedByUserId),
                'append_work_order_activity_from_whatsapp' => $this->executeAppendWorkOrderActivityFromWhatsApp($proposal, $confirmedByUserId),
                'organize_whatsapp_request' => $this->executeOrganizeWhatsAppRequest($proposal, $confirmedByUserId),
                'create_intervention_from_whatsapp' => $this->executeCreateInterventionFromWhatsApp($proposal, $confirmedByUserId),
                'update_intervention_schedule_from_whatsapp' => $this->executeUpdateInterventionScheduleFromWhatsApp($proposal, $confirmedByUserId),
                'append_work_order_activity_from_email' => $this->executeAppendWorkOrderActivityFromEmail($proposal, $confirmedByUserId),
                'create_intervention_from_email' => $this->executeCreateInterventionFromEmail($proposal, $confirmedByUserId),
                'update_intervention_schedule_from_email' => $this->executeUpdateInterventionScheduleFromEmail($proposal, $confirmedByUserId),
                default => ['ok' => false, 'error' => 'Tipo proposta non supportato.'],
            };

            if (($result['ok'] ?? false) !== true) {
                $resultError = $result['error'] ?? null;
                $errorMessage = is_string($resultError) && trim($resultError) !== ''
                    ? $resultError
                    : 'Esecuzione fallita.';

                $proposal->forceFill([
                    'status' => 'failed',
                    'error' => $errorMessage,
                    'executed_at' => now(),
                    'result' => $result,
                ])->save();

                $this->log($proposal, $confirmedByUserId, 'execute:'.$type, $payload, $result, 'error', $errorMessage);

                return $result;
            }

            $proposal->forceFill([
                'status' => 'executed',
                'executed_at' => now(),
                'result' => $result,
            ])->save();

            $this->log($proposal, $confirmedByUserId, 'execute:'.$type, $payload, $result, 'ok', null);

            return $result;
        } catch (Throwable $e) {
            $proposal->forceFill([
                'status' => 'failed',
                'error' => $e->getMessage(),
                'executed_at' => now(),
            ])->save();

            $this->log($proposal, $confirmedByUserId, 'execute:'.$type, $payload, null, 'error', $e->getMessage());

            return [
                'ok' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function executeStartTimePunch(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $interventionId = $this->payloadInt($payload, 'intervention_id');
        if ($interventionId <= 0) {
            return ['ok' => false, 'error' => 'intervention_id mancante.'];
        }

        /** @var Intervention $intervention */
        $intervention = Intervention::query()->findOrFail($interventionId);
        Gate::authorize('update', $intervention);

        $punch = app(StartTimePunchAction::class)->execute($intervention, $userId);

        return [
            'ok' => true,
            'time_punch_id' => $punch->id,
            'intervention_id' => $interventionId,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeStopTimePunch(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $interventionId = $this->payloadInt($payload, 'intervention_id');
        if ($interventionId <= 0) {
            return ['ok' => false, 'error' => 'intervention_id mancante.'];
        }

        /** @var Intervention $intervention */
        $intervention = Intervention::query()->findOrFail($interventionId);
        Gate::authorize('update', $intervention);

        $punch = app(StopTimePunchAction::class)->execute($intervention, $userId);

        return [
            'ok' => true,
            'time_punch_id' => $punch->id,
            'intervention_id' => $interventionId,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeUpsertDailyNote(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $noteDateRaw = $this->payloadNullableString($payload, 'note_date') ?? '';
        $body = $this->payloadNullableString($payload, 'body') ?? '';

        if (trim($noteDateRaw) === '' || trim($body) === '') {
            return ['ok' => false, 'error' => 'note_date/body mancanti.'];
        }

        $noteDate = CarbonImmutable::parse($noteDateRaw)->startOfDay();
        $note = app(UpsertDailyNoteForUserAction::class)->execute($userId, $noteDate, $body);

        return [
            'ok' => true,
            'daily_note_id' => $note->id,
            'note_date' => $note->note_date,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeChangeWorkOrderStatus(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $workOrderId = $this->payloadInt($payload, 'work_order_id');
        $toStatusCode = $this->payloadNullableString($payload, 'to_status_code') ?? '';
        $note = $payload['note'] ?? null;

        if ($workOrderId <= 0 || trim($toStatusCode) === '') {
            return ['ok' => false, 'error' => 'work_order_id/to_status_code mancanti.'];
        }

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);
        Gate::authorize('update', $workOrder);

        app(ChangeWorkOrderStatusByCodeAction::class)->execute(
            workOrderId: $workOrderId,
            toStatusCode: $toStatusCode,
            changedByUserId: $userId,
            note: is_string($note) ? $note : null,
        );

        return [
            'ok' => true,
            'work_order_id' => $workOrderId,
            'to_status_code' => $toStatusCode,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeAppendWorkOrderActivityFromWhatsApp(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $workOrderId = $this->payloadInt($payload, 'work_order_id');
        $body = $this->payloadNullableString($payload, 'body')
            ?? $this->payloadNullableString($payload, 'summary')
            ?? '';

        if ($workOrderId <= 0 || trim($body) === '') {
            return ['ok' => false, 'error' => 'work_order_id/body mancanti.'];
        }

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);
        Gate::authorize('update', $workOrder);

        $activity = app(AddWorkOrderActivityAction::class)->execute(
            workOrderId: $workOrderId,
            type: 'whatsapp',
            body: $body,
            createdByUserId: $userId,
        );

        $this->markWhatsAppMessageApplied($payload, $workOrderId, null);

        return [
            'ok' => true,
            'work_order_activity_id' => $activity->id,
            'work_order_id' => $workOrderId,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeCreateInterventionFromWhatsApp(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $workOrderId = $this->payloadInt($payload, 'work_order_id');
        $notes = $this->payloadNullableString($payload, 'notes')
            ?? $this->payloadNullableString($payload, 'body')
            ?? '';

        if ($workOrderId <= 0) {
            return ['ok' => false, 'error' => 'work_order_id mancante.'];
        }

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);
        Gate::authorize('update', $workOrder);

        $scheduledAt = $this->payloadNullableString($payload, 'scheduled_at');
        $intervention = app(CreateInterventionAction::class)->execute([
            'work_order_id' => $workOrderId,
            'performed_by_user_id' => $this->payloadNullableInt($payload, 'performed_by_user_id') ?? $userId,
            'scheduled_at' => $scheduledAt !== null && trim($scheduledAt) !== '' ? CarbonImmutable::parse($scheduledAt) : null,
            'notes' => trim($notes) !== '' ? $notes : null,
            'amount' => $this->payloadNullableFloat($payload, 'labor_amount') ?? $this->payloadNullableFloat($payload, 'amount'),
            'callout_fee' => $this->payloadNullableFloat($payload, 'callout_fee') ?? 0,
        ]);
        $this->syncManualMaterialLines($intervention, $payload);
        $this->syncInterventionEconomicAmounts($intervention, $payload);

        $this->markWhatsAppMessageApplied($payload, $workOrderId, (int) $intervention->id);

        return [
            'ok' => true,
            'intervention_id' => $intervention->id,
            'work_order_id' => $workOrderId,
            'scheduled_at' => $intervention->scheduled_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeOrganizeWhatsAppRequest(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);

        $messageId = $this->payloadNullableInt($payload, 'whatsapp_message_id');
        if ($messageId === null) {
            return ['ok' => false, 'error' => 'Messaggio WhatsApp mancante.'];
        }

        /** @var WhatsAppMessage|null $message */
        $message = WhatsAppMessage::query()->with('conversation')->find($messageId);
        if (! $message instanceof WhatsAppMessage || $message->conversation === null) {
            return ['ok' => false, 'error' => 'Conversazione non trovata.'];
        }

        $result = app(OrganizeWhatsAppRequestAction::class)->execute($message->conversation, [
            'customer_name' => $this->payloadNullableString($payload, 'customer_name') ?? '',
            'address' => $this->payloadNullableString($payload, 'address') ?? '',
            'city' => $this->payloadNullableString($payload, 'city') ?? '',
            'problem' => $this->payloadNullableString($payload, 'problem')
                ?? $this->payloadNullableString($payload, 'body')
                ?? $this->payloadNullableString($payload, 'summary')
                ?? '',
            'scheduled_at' => $this->payloadNullableString($payload, 'scheduled_at') ?? '',
        ], $userId);

        $this->markWhatsAppMessageApplied($payload, $result['work_order_id'], $result['intervention_id']);

        return ['ok' => true] + $result;
    }

    /**
     * @return array<string, mixed>
     */
    private function executeUpdateInterventionScheduleFromWhatsApp(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $interventionId = $this->payloadInt($payload, 'intervention_id');
        $scheduledAtRaw = $this->payloadNullableString($payload, 'scheduled_at');

        if ($interventionId <= 0 || $scheduledAtRaw === null || trim($scheduledAtRaw) === '') {
            return ['ok' => false, 'error' => 'intervention_id/scheduled_at mancanti.'];
        }

        /** @var Intervention $intervention */
        $intervention = Intervention::query()->findOrFail($interventionId);
        Gate::authorize('update', $intervention);

        $note = $this->payloadNullableString($payload, 'body')
            ?? $this->payloadNullableString($payload, 'notes');

        $intervention->forceFill([
            'scheduled_at' => CarbonImmutable::parse($scheduledAtRaw),
            'notes' => $note !== null && trim($note) !== ''
                ? trim(((string) ($intervention->notes ?? ''))."\n\n".$note)
                : $intervention->notes,
        ])->save();
        $this->syncManualMaterialLines($intervention, $payload);
        $this->syncInterventionEconomicAmounts($intervention, $payload);

        $this->markWhatsAppMessageApplied($payload, (int) $intervention->work_order_id, (int) $intervention->id);

        return [
            'ok' => true,
            'intervention_id' => $intervention->id,
            'work_order_id' => $intervention->work_order_id,
            'scheduled_at' => $intervention->scheduled_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeAppendWorkOrderActivityFromEmail(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $workOrderId = $this->payloadInt($payload, 'work_order_id');
        $body = $this->payloadNullableString($payload, 'body')
            ?? $this->payloadNullableString($payload, 'summary')
            ?? '';

        if ($workOrderId <= 0 || trim($body) === '') {
            return ['ok' => false, 'error' => 'work_order_id/body mancanti.'];
        }

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);
        Gate::authorize('update', $workOrder);

        $activity = app(AddWorkOrderActivityAction::class)->execute(
            workOrderId: $workOrderId,
            type: 'email',
            body: $body,
            createdByUserId: $userId,
        );

        $this->markEmailMessageApplied($payload, $workOrderId, null);

        return [
            'ok' => true,
            'work_order_activity_id' => $activity->id,
            'work_order_id' => $workOrderId,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeCreateInterventionFromEmail(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $workOrderId = $this->payloadInt($payload, 'work_order_id');
        $notes = $this->payloadNullableString($payload, 'notes')
            ?? $this->payloadNullableString($payload, 'body')
            ?? '';

        if ($workOrderId <= 0) {
            return ['ok' => false, 'error' => 'work_order_id mancante.'];
        }

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);
        Gate::authorize('update', $workOrder);

        $scheduledAt = $this->payloadNullableString($payload, 'scheduled_at');
        $intervention = app(CreateInterventionAction::class)->execute([
            'work_order_id' => $workOrderId,
            'performed_by_user_id' => $this->payloadNullableInt($payload, 'performed_by_user_id') ?? $userId,
            'scheduled_at' => $scheduledAt !== null && trim($scheduledAt) !== '' ? CarbonImmutable::parse($scheduledAt) : null,
            'notes' => trim($notes) !== '' ? $notes : null,
            'amount' => $this->payloadNullableFloat($payload, 'labor_amount') ?? $this->payloadNullableFloat($payload, 'amount'),
            'callout_fee' => $this->payloadNullableFloat($payload, 'callout_fee') ?? 0,
        ]);
        $this->syncManualMaterialLines($intervention, $payload);
        $this->syncInterventionEconomicAmounts($intervention, $payload);

        $this->markEmailMessageApplied($payload, $workOrderId, (int) $intervention->id);

        return [
            'ok' => true,
            'intervention_id' => $intervention->id,
            'work_order_id' => $workOrderId,
            'scheduled_at' => $intervention->scheduled_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeUpdateInterventionScheduleFromEmail(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $interventionId = $this->payloadInt($payload, 'intervention_id');
        $scheduledAtRaw = $this->payloadNullableString($payload, 'scheduled_at');

        if ($interventionId <= 0 || $scheduledAtRaw === null || trim($scheduledAtRaw) === '') {
            return ['ok' => false, 'error' => 'intervention_id/scheduled_at mancanti.'];
        }

        /** @var Intervention $intervention */
        $intervention = Intervention::query()->findOrFail($interventionId);
        Gate::authorize('update', $intervention);

        $note = $this->payloadNullableString($payload, 'body')
            ?? $this->payloadNullableString($payload, 'notes');

        $intervention->forceFill([
            'scheduled_at' => CarbonImmutable::parse($scheduledAtRaw),
            'notes' => $note !== null && trim($note) !== ''
                ? trim(((string) ($intervention->notes ?? ''))."\n\n".$note)
                : $intervention->notes,
        ])->save();
        $this->syncManualMaterialLines($intervention, $payload);
        $this->syncInterventionEconomicAmounts($intervention, $payload);

        $this->markEmailMessageApplied($payload, (int) $intervention->work_order_id, (int) $intervention->id);

        return [
            'ok' => true,
            'intervention_id' => $intervention->id,
            'work_order_id' => $intervention->work_order_id,
            'scheduled_at' => $intervention->scheduled_at?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeFinalizeInterventionReport(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $interventionId = $this->payloadInt($payload, 'intervention_id');
        $travelKm = $this->payloadInt($payload, 'travel_km');
        $travelMinutes = $this->payloadInt($payload, 'travel_minutes');
        $outcomeCode = $this->payloadNullableString($payload, 'outcome_code') ?? '';
        $notes = $payload['notes'] ?? null;

        if ($interventionId <= 0 || trim($outcomeCode) === '') {
            return ['ok' => false, 'error' => 'intervention_id/outcome_code mancanti.'];
        }

        if (! in_array($outcomeCode, ['completed', 'quotation', 'suspended'], true)) {
            return ['ok' => false, 'error' => 'outcome_code non valido.'];
        }

        /** @var 'completed'|'quotation'|'suspended' $outcomeCodeTyped */
        $outcomeCodeTyped = $outcomeCode;

        /** @var Intervention $intervention */
        $intervention = Intervention::query()->findOrFail($interventionId);
        Gate::authorize('update', $intervention);

        $updated = app(FinalizeInterventionReportAction::class)->execute(
            intervention: $intervention,
            validatedByUserId: $userId,
            travelKm: $travelKm,
            travelMinutes: $travelMinutes,
            notes: is_string($notes) ? $notes : null,
            outcomeCode: $outcomeCodeTyped,
        );

        return [
            'ok' => true,
            'intervention_id' => $interventionId,
            'validated_at' => (string) $updated->validated_at,
            'outcome_code' => (string) $updated->outcome_code,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeCreateInventoryMovement(AiActionProposal $proposal, int|string $userId): array
    {
        Gate::authorize('create', InventoryMovement::class);

        $payload = $this->payloadOf($proposal);

        $productId = $this->payloadInt($payload, 'product_id');
        $movementType = $this->payloadNullableString($payload, 'movement_type') ?? '';
        $quantity = $this->payloadInt($payload, 'quantity');
        $fromWarehouseId = $this->payloadNullableInt($payload, 'from_warehouse_id');
        $toWarehouseId = $this->payloadNullableInt($payload, 'to_warehouse_id');
        $unitPrice = $this->payloadNullableFloat($payload, 'unit_price');
        $note = $this->payloadNullableString($payload, 'note');
        $workOrderId = $this->payloadNullableInt($payload, 'work_order_id');
        $interventionId = $this->payloadNullableInt($payload, 'intervention_id');

        if ($productId <= 0 || $quantity <= 0) {
            return ['ok' => false, 'error' => 'product_id/quantity non validi.'];
        }

        if (! in_array($movementType, ['carico', 'scarico', 'trasferimento', 'reso'], true)) {
            return ['ok' => false, 'error' => 'movement_type non valido.'];
        }

        if ($movementType === 'carico' && $toWarehouseId === null) {
            return ['ok' => false, 'error' => 'to_warehouse_id richiesto per carico.'];
        }
        if ($movementType === 'scarico' && $fromWarehouseId === null) {
            return ['ok' => false, 'error' => 'from_warehouse_id richiesto per scarico.'];
        }
        if ($movementType === 'trasferimento' && ($fromWarehouseId === null || $toWarehouseId === null)) {
            return ['ok' => false, 'error' => 'from_warehouse_id e to_warehouse_id richiesti per trasferimento.'];
        }

        if (in_array($movementType, ['scarico', 'trasferimento'], true) && $fromWarehouseId !== null) {
            $stockError = $this->ensureEnoughStock($productId, $fromWarehouseId, $quantity);
            if ($stockError !== null) {
                return ['ok' => false, 'error' => $stockError];
            }
        }

        $settings = app(SettingsRepository::class);
        if ($workOrderId !== null && ! $settings->getBool('inventory.allow_link_work_orders', true)) {
            return ['ok' => false, 'error' => 'Link a commessa non abilitato dalle impostazioni.'];
        }
        if ($interventionId !== null && ! $settings->getBool('inventory.allow_link_interventions', true)) {
            return ['ok' => false, 'error' => 'Link a intervento non abilitato dalle impostazioni.'];
        }

        $movement = app(CreateInventoryMovementAction::class)->execute(
            productId: $productId,
            movementType: $movementType,
            quantity: $quantity,
            fromWarehouseId: $fromWarehouseId,
            toWarehouseId: $toWarehouseId,
            note: $note,
            workOrderId: $workOrderId,
            interventionId: $interventionId,
            createdByUserId: $userId,
            unitPrice: $unitPrice,
        );

        return [
            'ok' => true,
            'inventory_movement_id' => $movement->id,
            'product_id' => $productId,
            'movement_type' => $movementType,
            'quantity' => $quantity,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeAllocateInventoryToWorkOrder(AiActionProposal $proposal, int|string $userId): array
    {
        Gate::authorize('create', InventoryMovement::class);

        $payload = $this->payloadOf($proposal);

        $productId = $this->payloadInt($payload, 'product_id');
        $quantity = $this->payloadInt($payload, 'quantity');
        $fromWarehouseId = $this->payloadInt($payload, 'from_warehouse_id');
        $workOrderId = $this->payloadInt($payload, 'work_order_id');
        $unitPrice = $this->payloadNullableFloat($payload, 'unit_price');
        $note = $this->payloadNullableString($payload, 'note');

        if ($productId <= 0 || $quantity <= 0 || $fromWarehouseId <= 0 || $workOrderId <= 0) {
            return ['ok' => false, 'error' => 'Parametri non validi per allocazione su commessa.'];
        }

        $settings = app(SettingsRepository::class);
        if (! $settings->getBool('inventory.allow_link_work_orders', true)) {
            return ['ok' => false, 'error' => 'Link a commessa non abilitato dalle impostazioni.'];
        }

        $stockError = $this->ensureEnoughStock($productId, $fromWarehouseId, $quantity);
        if ($stockError !== null) {
            return ['ok' => false, 'error' => $stockError];
        }

        $movement = app(CreateInventoryMovementAction::class)->execute(
            productId: $productId,
            movementType: 'scarico',
            quantity: $quantity,
            fromWarehouseId: $fromWarehouseId,
            toWarehouseId: null,
            note: $note,
            workOrderId: $workOrderId,
            interventionId: null,
            createdByUserId: $userId,
            unitPrice: $unitPrice,
        );

        return [
            'ok' => true,
            'inventory_movement_id' => $movement->id,
            'work_order_id' => $workOrderId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeAllocateInventoryToIntervention(AiActionProposal $proposal, int|string $userId): array
    {
        Gate::authorize('create', InventoryMovement::class);

        $payload = $this->payloadOf($proposal);

        $productId = $this->payloadInt($payload, 'product_id');
        $quantity = $this->payloadInt($payload, 'quantity');
        $fromWarehouseId = $this->payloadInt($payload, 'from_warehouse_id');
        $interventionId = $this->payloadInt($payload, 'intervention_id');
        $unitPrice = $this->payloadNullableFloat($payload, 'unit_price');
        $note = $this->payloadNullableString($payload, 'note');

        if ($productId <= 0 || $quantity <= 0 || $fromWarehouseId <= 0 || $interventionId <= 0) {
            return ['ok' => false, 'error' => 'Parametri non validi per allocazione su intervento.'];
        }

        $settings = app(SettingsRepository::class);
        if (! $settings->getBool('inventory.allow_link_interventions', true)) {
            return ['ok' => false, 'error' => 'Link a intervento non abilitato dalle impostazioni.'];
        }

        $stockError = $this->ensureEnoughStock($productId, $fromWarehouseId, $quantity);
        if ($stockError !== null) {
            return ['ok' => false, 'error' => $stockError];
        }

        $movement = app(CreateInventoryMovementAction::class)->execute(
            productId: $productId,
            movementType: 'scarico',
            quantity: $quantity,
            fromWarehouseId: $fromWarehouseId,
            toWarehouseId: null,
            note: $note,
            workOrderId: null,
            interventionId: $interventionId,
            createdByUserId: $userId,
            unitPrice: $unitPrice,
        );

        return [
            'ok' => true,
            'inventory_movement_id' => $movement->id,
            'intervention_id' => $interventionId,
            'product_id' => $productId,
            'quantity' => $quantity,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeCreateProductionOrder(AiActionProposal $proposal, int|string $userId): array
    {
        Gate::authorize('create', ProductionOrder::class);

        $payload = $this->payloadOf($proposal);

        $productId = $this->payloadInt($payload, 'product_id');
        $quantity = $this->payloadInt($payload, 'quantity');
        $workOrderId = $this->payloadNullableInt($payload, 'work_order_id');
        $notes = $this->payloadNullableString($payload, 'notes');

        if ($productId <= 0 || $quantity <= 0) {
            return ['ok' => false, 'error' => 'product_id/quantity non validi.'];
        }

        $order = app(CreateProductionOrderAction::class)->execute(
            productId: $productId,
            quantity: $quantity,
            workOrderId: $workOrderId,
            notes: $notes,
        );

        $code = data_get($order, 'code', '');
        $status = data_get($order, 'status', '');

        return [
            'ok' => true,
            'production_order_id' => $order->id,
            'code' => is_string($code) ? $code : (string) $code,
            'status' => is_string($status) ? $status : (string) $status,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeAdvanceProductionPhase(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $phaseId = $this->payloadInt($payload, 'production_phase_id');
        $toStatus = $this->payloadNullableString($payload, 'to_status') ?? '';

        if ($phaseId <= 0 || ! in_array($toStatus, ['in_progress', 'completed'], true)) {
            return ['ok' => false, 'error' => 'production_phase_id/to_status non validi.'];
        }

        /** @var ProductionPhase $phase */
        $phase = ProductionPhase::query()->findOrFail($phaseId);
        Gate::authorize('update', $phase);

        $updated = app(AdvanceProductionPhaseAction::class)->execute($phase, $toStatus);

        $statusValue = data_get($updated, 'status', '');

        return [
            'ok' => true,
            'production_phase_id' => $updated->id,
            'status' => is_string($statusValue) ? $statusValue : (string) $statusValue,
            'started_at' => data_get($updated, 'started_at'),
            'completed_at' => data_get($updated, 'completed_at'),
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function payloadInt(array $payload, string $key, int $default = 0): int
    {
        $value = $payload[$key] ?? null;

        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return (int) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        return $default;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function payloadNullableInt(array $payload, string $key): ?int
    {
        if (! array_key_exists($key, $payload)) {
            return null;
        }

        $value = $payload[$key];

        if ($value === null) {
            return null;
        }

        if (is_int($value)) {
            return $value;
        }

        if (is_float($value)) {
            return (int) $value;
        }

        if (is_string($value) && is_numeric($value)) {
            return (int) $value;
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function payloadNullableString(array $payload, string $key): ?string
    {
        if (! array_key_exists($key, $payload)) {
            return null;
        }

        $value = $payload[$key];

        if ($value === null) {
            return null;
        }

        if (is_string($value)) {
            return $value;
        }

        if (is_int($value) || is_float($value) || is_bool($value)) {
            return (string) $value;
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function payloadNullableFloat(array $payload, string $key): ?float
    {
        if (! array_key_exists($key, $payload)) {
            return null;
        }

        $value = $payload[$key];

        if ($value === null) {
            return null;
        }

        if (is_float($value) || is_int($value)) {
            return (float) $value;
        }

        if (is_string($value)) {
            $normalized = str_replace(',', '.', trim($value));

            if (is_numeric($normalized)) {
                return (float) $normalized;
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function executeCreateComplianceDocument(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);

        $type = $this->payloadNullableString($payload, 'type') ?? '';
        $title = $this->payloadNullableString($payload, 'title') ?? '';
        $workOrderId = $this->payloadNullableInt($payload, 'work_order_id');
        $workOrder = null;

        if (trim($type) === '' || trim($title) === '') {
            return ['ok' => false, 'error' => 'type/title mancanti.'];
        }

        if ($workOrderId !== null) {
            /** @var WorkOrder $workOrder */
            $workOrder = WorkOrder::query()->findOrFail($workOrderId);
            Gate::authorize('update', $workOrder);
        }

        /** @var ComplianceDocument $document */
        $document = ComplianceDocument::query()->create([
            'type' => $type,
            'title' => $title,
            'status' => $this->payloadNullableString($payload, 'status') ?? 'valid',
            'is_required' => (bool) ($payload['is_required'] ?? true),
            'issued_on' => $this->payloadNullableString($payload, 'issued_on'),
            'expires_on' => $this->payloadNullableString($payload, 'expires_on'),
            'work_order_id' => $workOrderId,
            'customer_id' => $this->payloadNullableInt($payload, 'customer_id'),
            'employee_id' => $this->payloadNullableInt($payload, 'employee_id'),
            'vehicle_id' => $this->payloadNullableInt($payload, 'vehicle_id'),
            'subcontractor_id' => $this->payloadNullableInt($payload, 'subcontractor_id'),
            'equipment_id' => $this->payloadNullableInt($payload, 'equipment_id'),
            'media_id' => $this->payloadNullableInt($payload, 'media_id'),
            'uploaded_by_user_id' => $userId,
            'notes' => $this->payloadNullableString($payload, 'notes'),
            'meta' => ['created_from_ai_proposal' => $proposal->public_id],
        ]);

        if ($workOrderId !== null) {
            app(EvaluateWorkOrderReadinessAction::class)->execute($workOrder);
        }

        return [
            'ok' => true,
            'compliance_document_id' => $document->id,
            'type' => (string) data_get($document, 'type', ''),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeCreateWorkOrderChangeOrder(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);

        $workOrderId = $this->payloadInt($payload, 'work_order_id');
        $title = $this->payloadNullableString($payload, 'title') ?? '';

        if ($workOrderId <= 0 || trim($title) === '') {
            return ['ok' => false, 'error' => 'work_order_id/title mancanti.'];
        }

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);
        Gate::authorize('update', $workOrder);

        /** @var WorkOrderChangeOrder $changeOrder */
        $changeOrder = WorkOrderChangeOrder::query()->create([
            'work_order_id' => $workOrderId,
            'requested_by_user_id' => $userId,
            'title' => $title,
            'description' => $this->payloadNullableString($payload, 'description'),
            'estimated_amount' => $this->payloadNullableFloat($payload, 'estimated_amount'),
            'status' => $this->payloadNullableString($payload, 'status') ?? 'proposed',
            'meta' => ['created_from_ai_proposal' => $proposal->public_id],
        ]);

        app(EvaluateWorkOrderReadinessAction::class)->execute($workOrder);

        return [
            'ok' => true,
            'work_order_change_order_id' => $changeOrder->id,
            'work_order_id' => $workOrderId,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeGeneratePosDraft(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $workOrderId = $this->payloadInt($payload, 'work_order_id');

        if ($workOrderId <= 0) {
            return ['ok' => false, 'error' => 'work_order_id mancante.'];
        }

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->findOrFail($workOrderId);
        Gate::authorize('update', $workOrder);

        $document = app(GeneratePosDraftForWorkOrderAction::class)->execute($workOrder);

        return [
            'ok' => true,
            'work_order_id' => $workOrderId,
            'compliance_document_id' => $document->id,
            'type' => (string) data_get($document, 'type', ''),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeApplyPublicProcurementTenderDraft(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $draftId = $this->payloadNullableInt($payload, 'draft_id');
        $draftPublicId = $this->payloadNullableString($payload, 'draft_public_id');

        /** @var PublicProcurementAiExtraction|null $draft */
        $draft = PublicProcurementAiExtraction::query()
            ->when($draftId !== null, fn ($query) => $query->where('id', $draftId))
            ->when($draftId === null && $draftPublicId !== null, fn ($query) => $query->where('public_id', $draftPublicId))
            ->first();

        if (! $draft instanceof PublicProcurementAiExtraction) {
            return ['ok' => false, 'error' => 'Bozza AI appalto non trovata.'];
        }

        $opportunity = app(ApplyTenderOpportunityDraftAction::class)->execute($draft);

        return [
            'ok' => true,
            'tender_opportunity_id' => $opportunity->id,
            'status' => (string) data_get($opportunity, 'status', ''),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeCreatePublicProcurementContractFromOpportunity(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $opportunityId = $this->payloadInt($payload, 'tender_opportunity_id');
        if ($opportunityId <= 0) {
            return ['ok' => false, 'error' => 'tender_opportunity_id mancante.'];
        }

        /** @var PublicProcurementTenderOpportunity $opportunity */
        $opportunity = PublicProcurementTenderOpportunity::query()->findOrFail($opportunityId);
        $contract = app(CreateContractFromOpportunityAction::class)->execute($opportunity);

        return [
            'ok' => true,
            'public_procurement_contract_id' => $contract->id,
            'work_order_id' => data_get($contract, 'work_order_id'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeImportPublicProcurementPriceItems(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $contractId = $this->payloadInt($payload, 'public_procurement_contract_id');
        $items = $payload['items'] ?? [];
        if ($contractId <= 0 || ! is_array($items)) {
            return ['ok' => false, 'error' => 'public_procurement_contract_id/items mancanti.'];
        }

        /** @var PublicProcurementContract $contract */
        $contract = PublicProcurementContract::query()->findOrFail($contractId);
        $created = 0;
        foreach ($items as $item) {
            if (! is_array($item)) {
                continue;
            }

            $itemPayload = $this->payloadOfArray($item);

            $description = $this->payloadNullableString($itemPayload, 'description') ?? '';
            if (trim($description) === '') {
                continue;
            }

            $quantity = $this->payloadNullableFloat($itemPayload, 'planned_quantity') ?? $this->payloadNullableFloat($itemPayload, 'quantity') ?? 0.0;
            $unitPrice = $this->payloadNullableFloat($itemPayload, 'unit_price') ?? 0.0;

            PublicProcurementPriceItem::query()->create([
                'public_procurement_contract_id' => $contract->id,
                'code' => $this->payloadNullableString($itemPayload, 'code'),
                'category' => $this->payloadNullableString($itemPayload, 'category'),
                'description' => $description,
                'unit' => $this->payloadNullableString($itemPayload, 'unit') ?? 'cad',
                'planned_quantity' => $quantity,
                'unit_price' => $unitPrice,
                'planned_total' => round($quantity * $unitPrice, 2),
                'meta' => ['created_from_ai_proposal' => $proposal->public_id],
            ]);
            $created++;
        }

        return [
            'ok' => true,
            'public_procurement_contract_id' => $contract->id,
            'created_items' => $created,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function executeConfirmEnergyBrokerDocumentImport(AiActionProposal $proposal, int|string $userId): array
    {
        $payload = $this->payloadOf($proposal);
        $documentId = $this->payloadInt($payload, 'document_id');

        if ($documentId <= 0) {
            return ['ok' => false, 'error' => 'document_id mancante.'];
        }

        /** @var EnergyBrokerDocument $document */
        $document = EnergyBrokerDocument::query()->findOrFail($documentId);
        Gate::authorize('update', $document);

        $result = app(ConfirmEnergyBrokerDocumentImportAction::class)->execute($document);

        return [
            'ok' => true,
            'energy_broker_document_id' => $documentId,
            'status' => (string) ($result['status'] ?? 'unknown'),
            'customer_id' => $result['customer_id'] ?? null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function payloadOf(AiActionProposal $proposal): array
    {
        $rawPayload = $proposal->payload;

        if (! is_array($rawPayload)) {
            return [];
        }

        $payload = [];
        foreach ($rawPayload as $key => $value) {
            if (! is_string($key)) {
                continue;
            }

            $payload[$key] = $value;
        }

        return $payload;
    }

    /**
     * @param  array<mixed, mixed>  $rawPayload
     * @return array<string, mixed>
     */
    private function payloadOfArray(array $rawPayload): array
    {
        $payload = [];
        foreach ($rawPayload as $key => $value) {
            if (! is_string($key)) {
                continue;
            }

            $payload[$key] = $value;
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function syncManualMaterialLines(Intervention $intervention, array $payload): void
    {
        $materialLines = is_array($payload['material_lines'] ?? null) ? $payload['material_lines'] : [];
        if ($materialLines === []) {
            return;
        }

        $lines = collect($materialLines)
            ->map(static function (mixed $line): array {
                $line = is_array($line) ? $line : [];

                $descriptionRaw = $line['description'] ?? '';
                $description = is_string($descriptionRaw) ? trim($descriptionRaw) : '';

                $quantityRaw = $line['quantity'] ?? 1;
                $quantity = is_numeric($quantityRaw) ? (float) $quantityRaw : 1;

                $unitPriceRaw = $line['unit_price'] ?? 0;
                $unitPrice = is_numeric($unitPriceRaw) ? (float) $unitPriceRaw : 0;

                $safeQuantity = $quantity > 0 ? $quantity : 1.0;

                return [
                    'description' => $description,
                    'quantity' => $safeQuantity,
                    'unit_price' => $unitPrice,
                    'line_total' => round($safeQuantity * $unitPrice, 2),
                ];
            })
            ->filter(static fn (array $line): bool => $line['description'] !== '' && $line['unit_price'] > 0)
            ->values();

        if ($lines->isEmpty()) {
            return;
        }

        $intervention->materialLines()->createMany($lines->all());
        $intervention->refreshEconomicTotals();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function syncInterventionEconomicAmounts(Intervention $intervention, array $payload): void
    {
        $laborAmount = $this->payloadNullableFloat($payload, 'labor_amount') ?? $this->payloadNullableFloat($payload, 'amount');
        $calloutFee = $this->payloadNullableFloat($payload, 'callout_fee');
        $updates = [];

        if ($laborAmount !== null && $laborAmount >= 0) {
            $updates['amount'] = round($laborAmount, 2);
        }

        if ($calloutFee !== null && $calloutFee >= 0) {
            $updates['callout_fee'] = round($calloutFee, 2);
        }

        if ($updates === []) {
            return;
        }

        $intervention->forceFill($updates)->save();
        $intervention->refreshEconomicTotals();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function markWhatsAppMessageApplied(array $payload, ?int $workOrderId, ?int $interventionId): void
    {
        $messageId = $this->payloadNullableInt($payload, 'whatsapp_message_id');
        if ($messageId === null) {
            return;
        }

        /** @var WhatsAppMessage|null $message */
        $message = WhatsAppMessage::query()->with('conversation')->find($messageId);
        if (! ($message instanceof WhatsAppMessage)) {
            return;
        }

        $message->forceFill([
            'work_order_id' => $workOrderId,
            'intervention_id' => $interventionId,
            'processing_status' => 'processed',
            'processed_at' => now(),
        ])->save();

        // Checkpoint: everything up to the latest message is now handled, and
        // the live proposal is cleared so new messages start a fresh one.
        $conversation = $message->conversation;
        if ($conversation !== null) {
            $latestMessageId = (int) $conversation->messages()->max('id');
            $conversation->forceFill([
                'approved_up_to_message_id' => $latestMessageId > 0 ? $latestMessageId : (int) $message->id,
                'active_proposal_id' => null,
            ])->save();
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function markEmailMessageApplied(array $payload, ?int $workOrderId, ?int $interventionId): void
    {
        $messageId = $this->payloadNullableInt($payload, 'email_message_id');
        if ($messageId === null) {
            return;
        }

        /** @var EmailMessage|null $message */
        $message = EmailMessage::query()->find($messageId);
        if (! ($message instanceof EmailMessage)) {
            return;
        }

        $message->forceFill([
            'work_order_id' => $workOrderId,
            'intervention_id' => $interventionId,
            'processing_status' => 'processed',
            'processed_at' => now(),
        ])->save();
    }

    private function ensureEnoughStock(int $productId, int $warehouseId, int $quantity): ?string
    {
        $snapshot = app(GetStockSnapshotAction::class)->execute($productId, $warehouseId);
        $available = (int) ($snapshot['aggregate'] ?? 0);

        if ($available < $quantity) {
            return "Giacenza insufficiente nel magazzino selezionato: disponibili {$available}, richiesti {$quantity}.";
        }

        return null;
    }

    /**
     * @param  array<string, mixed>|null  $arguments
     * @param  array<string, mixed>|null  $response
     */
    private function log(AiActionProposal $proposal, int|string $userId, string $toolName, ?array $arguments, ?array $response, string $status, ?string $error): void
    {
        AiToolLog::query()->create([
            'ai_thread_id' => $proposal->ai_thread_id,
            'ai_action_proposal_id' => $proposal->id,
            'user_id' => $userId,
            'tool_name' => $toolName,
            'arguments' => $arguments,
            'response' => $response,
            'status' => $status,
            'error' => $error,
        ]);
    }
}
