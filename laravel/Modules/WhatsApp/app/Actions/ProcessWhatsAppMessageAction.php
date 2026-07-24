<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Actions;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\AiAssistant\Actions\CreateAiActionProposalAction;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiMessage;
use Modules\AiAssistant\Models\AiThread;
use Modules\WhatsApp\AI\WhatsAppMessageClassifierInterface;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Settings\WhatsAppSettings;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;
use Spatie\QueueableAction\QueueableAction;
use Throwable;

final class ProcessWhatsAppMessageAction
{
    use QueueableAction;

    public function __construct(
        private ResolveWhatsAppMessageContextAction $resolveContext,
        private WhatsAppMessageClassifierInterface $classifier,
        private WhatsAppSettings $settings,
        private AddWorkOrderActivityAction $addWorkOrderActivity,
        private CreateAiActionProposalAction $createProposal,
    ) {}

    public function execute(int $messageId): WhatsAppMessage
    {
        /** @var WhatsAppMessage $message */
        $message = WhatsAppMessage::query()->with('conversation')->findOrFail($messageId);

        if ($message->processing_status === 'processed') {
            return $message;
        }

        $message->forceFill(['processing_status' => 'processing', 'error' => null])->save();

        try {
            $conversation = $message->conversation;
            if (! $conversation instanceof WhatsAppConversation) {
                throw new \RuntimeException("Conversazione WhatsApp mancante per il messaggio {$message->id}.");
            }

            $context = $this->resolveContext->execute($conversation);
            $classification = $this->classifier->classify($message, $context);

            $message->forceFill([
                'ai_result' => $classification,
                'work_order_id' => $this->positiveInt($classification['work_order_id'] ?? null),
                'intervention_id' => $this->positiveInt($classification['intervention_id'] ?? null),
            ])->save();

            if ($this->canAutoArchive($classification)) {
                $this->archiveAsWorkOrderActivity($message, $classification);
                $message->forceFill([
                    'processing_status' => 'processed',
                    'processed_at' => now(),
                ])->save();

                return $message->refresh();
            }

            if ($this->shouldIgnore($classification)) {
                $message->forceFill([
                    'processing_status' => 'processed',
                    'processed_at' => now(),
                ])->save();

                return $message->refresh();
            }

            $proposal = $this->createApprovalProposal($message, $classification, $context);
            if ($proposal !== null) {
                $message->forceFill([
                    'ai_action_proposal_id' => $proposal->id,
                    'processing_status' => 'needs_review',
                    'processed_at' => now(),
                ])->save();

                return $message->refresh();
            }

            $message->forceFill([
                'processing_status' => 'needs_review',
                'processed_at' => now(),
                'error' => 'Nessun approvatore configurato per WhatsApp AI.',
            ])->save();

            return $message->refresh();
        } catch (Throwable $e) {
            $message->forceFill([
                'processing_status' => 'failed',
                'processed_at' => now(),
                'error' => $e->getMessage(),
            ])->save();

            return $message->refresh();
        }
    }

    /**
     * @param  array<string, mixed>  $classification
     */
    private function canAutoArchive(array $classification): bool
    {
        return $this->settings->autoArchiveCertainNotes()
            && (bool) ($classification['requires_approval'] ?? true) === false
            && (int) ($classification['confidence'] ?? 0) >= $this->settings->confidenceThreshold()
            && (string) ($classification['intent'] ?? '') === 'work_order_note'
            && $this->positiveInt($classification['work_order_id'] ?? null) !== null;
    }

    /**
     * @param  array<string, mixed>  $classification
     */
    private function shouldIgnore(array $classification): bool
    {
        $action = $this->stringKeyed($classification['proposed_action'] ?? null);

        return (string) ($action['type'] ?? '') === 'none'
            && (bool) ($classification['requires_approval'] ?? false) === false;
    }

    /**
     * @param  array<string, mixed>  $classification
     */
    private function archiveAsWorkOrderActivity(WhatsAppMessage $message, array $classification): void
    {
        $workOrderId = $this->positiveInt($classification['work_order_id'] ?? null);
        if ($workOrderId === null) {
            return;
        }

        $summary = trim((string) ($classification['summary'] ?? ''));
        $body = $summary !== '' ? $summary : trim((string) $message->body);

        $this->addWorkOrderActivity->execute(
            $workOrderId,
            'whatsapp',
            $body,
            null,
            $message->received_at instanceof Carbon
                ? CarbonImmutable::instance($message->received_at)
                : CarbonImmutable::now(),
        );
    }

    /**
     * @param  array<string, mixed>  $classification
     * @param  array<string, mixed>  $context
     */
    private function createApprovalProposal(WhatsAppMessage $message, array $classification, array $context): ?AiActionProposal
    {
        $approverId = $this->resolveApproverId();
        if ($approverId === null) {
            return null;
        }

        $thread = $this->threadFor($message, $approverId, $context);
        $action = $this->stringKeyed($classification['proposed_action'] ?? null);
        $type = $this->proposalType($action);
        $payload = $this->stringKeyed($action['payload'] ?? null);
        $payload['whatsapp_message_id'] = (int) $message->id;
        $payload['source'] = 'whatsapp';

        $summary = trim((string) ($classification['summary'] ?? ''));
        if ($summary !== '' && ! isset($payload['body'])) {
            $payload['body'] = $summary;
        }

        $preview = trim((string) ($action['preview'] ?? ''));
        if ($preview === '') {
            $preview = $this->defaultPreview($type, $message, $classification);
        }

        // Dedup: keep a single live pending proposal per conversation and
        // update it as new messages arrive, instead of piling up duplicates.
        $active = $this->activePendingProposal($message->conversation);
        if ($active instanceof AiActionProposal) {
            $active->forceFill(['type' => $type, 'payload' => $payload, 'preview' => $preview])->save();

            return $active;
        }

        $proposal = $this->createProposal->execute($thread, $approverId, $type, $payload, $preview);

        if ($message->conversation instanceof WhatsAppConversation) {
            $message->conversation->forceFill(['active_proposal_id' => $proposal->id])->save();
        }

        return $proposal;
    }

    private function activePendingProposal(?WhatsAppConversation $conversation): ?AiActionProposal
    {
        if (! $conversation instanceof WhatsAppConversation || $conversation->active_proposal_id === null) {
            return null;
        }

        /** @var AiActionProposal|null $proposal */
        $proposal = AiActionProposal::query()->find($conversation->active_proposal_id);

        return $proposal instanceof AiActionProposal && $proposal->status === 'pending' ? $proposal : null;
    }

    /**
     * @param  array<string, mixed>  $action
     */
    private function proposalType(array $action): string
    {
        $type = (string) ($action['type'] ?? '');

        return in_array($type, [
            'append_work_order_activity_from_whatsapp',
            'create_intervention_from_whatsapp',
            'update_intervention_schedule_from_whatsapp',
            'organize_whatsapp_request',
        ], true) ? $type : 'append_work_order_activity_from_whatsapp';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function threadFor(WhatsAppMessage $message, int $approverId, array $context): AiThread
    {
        $conversation = $message->conversation;
        $phoneE164 = $conversation instanceof WhatsAppConversation ? (string) $conversation->phone_e164 : '';

        /** @var AiThread|null $existing */
        $existing = AiThread::query()
            ->where('created_by_user_id', $approverId)
            ->where('panel_id', 'admin')
            ->where('meta->source', 'whatsapp')
            ->where('meta->conversation_id', (int) $message->conversation_id)
            ->first();

        if ($existing instanceof AiThread) {
            $existing->forceFill(['last_message_at' => now()])->save();
            $this->appendThreadMessage($existing, $message, $context);

            return $existing;
        }

        /** @var AiThread $thread */
        $thread = AiThread::query()->create([
            'public_id' => (string) Str::uuid(),
            'created_by_user_id' => $approverId,
            'panel_id' => 'admin',
            'last_message_at' => now(),
            'meta' => [
                'source' => 'whatsapp',
                'conversation_id' => (int) $message->conversation_id,
                'phone_e164' => $phoneE164,
            ],
        ]);

        $this->appendThreadMessage($thread, $message, $context);

        return $thread;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function appendThreadMessage(AiThread $thread, WhatsAppMessage $message, array $context): void
    {
        AiMessage::query()->create([
            'ai_thread_id' => $thread->id,
            'user_id' => null,
            'role' => 'user',
            'content' => 'WhatsApp: '.(string) $message->body,
            'payload' => [
                'source' => 'whatsapp',
                'whatsapp_message_id' => (int) $message->id,
                'context' => $context,
            ],
        ]);
    }

    private function resolveApproverId(): ?int
    {
        $configured = $this->settings->defaultApprovalUserId();
        if ($configured !== null && User::query()->whereKey($configured)->exists()) {
            return $configured;
        }

        $firstUserId = User::query()->orderBy('id')->value('id');

        return is_numeric($firstUserId) ? (int) $firstUserId : null;
    }

    /**
     * @param  array<string, mixed>  $classification
     */
    private function defaultPreview(string $type, WhatsAppMessage $message, array $classification): string
    {
        $summary = trim((string) ($classification['summary'] ?? ''));
        $summary = $summary !== '' ? $summary : mb_substr((string) $message->body, 0, 300);

        return match ($type) {
            'organize_whatsapp_request' => 'Nuova richiesta: '.$summary,
            'create_intervention_from_whatsapp' => 'Creare un intervento da WhatsApp: '.$summary,
            'update_intervention_schedule_from_whatsapp' => 'Aggiornare la pianificazione intervento da WhatsApp: '.$summary,
            default => 'Archiviare sulla commessa il messaggio WhatsApp: '.$summary,
        };
    }

    private function positiveInt(mixed $value): ?int
    {
        if (! is_numeric($value)) {
            return null;
        }

        $int = (int) $value;

        return $int > 0 ? $int : null;
    }

    /**
     * @return array<string, mixed>
     */
    private function stringKeyed(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $result = [];
        foreach ($value as $key => $item) {
            if (is_string($key)) {
                $result[$key] = $item;
            }
        }

        return $result;
    }
}
