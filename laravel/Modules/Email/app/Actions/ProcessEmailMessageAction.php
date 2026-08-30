<?php

declare(strict_types=1);

namespace Modules\Email\Actions;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Modules\AiAssistant\Actions\CreateAiActionProposalAction;
use Modules\AiAssistant\Exceptions\AiActionNotAllowedException;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiMessage;
use Modules\AiAssistant\Models\AiThread;
use Modules\Email\AI\EmailMessageClassifierInterface;
use Modules\Email\Models\EmailConversation;
use Modules\Email\Models\EmailMessage;
use Modules\Email\Settings\EmailSettings;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;
use Spatie\QueueableAction\QueueableAction;
use Throwable;

final class ProcessEmailMessageAction
{
    use QueueableAction;

    public function __construct(
        private ResolveEmailMessageContextAction $resolveContext,
        private EmailMessageClassifierInterface $classifier,
        private EmailSettings $settings,
        private AddWorkOrderActivityAction $addWorkOrderActivity,
        private CreateAiActionProposalAction $createProposal,
    ) {}

    public function execute(int $messageId): EmailMessage
    {
        /** @var EmailMessage $message */
        $message = EmailMessage::query()->with('conversation')->findOrFail($messageId);

        if ($message->processing_status === 'processed') {
            return $message;
        }

        $message->forceFill(['processing_status' => 'processing', 'error' => null])->save();

        $conversation = $message->conversation;
        if (! $conversation instanceof EmailConversation) {
            $message->forceFill([
                'processing_status' => 'failed',
                'processed_at' => now(),
                'error' => 'Conversazione email mancante.',
            ])->save();

            return $message->refresh();
        }

        try {
            $context = $this->resolveContext->execute($conversation);
            $classification = $this->classifier->classify($message, $context);

            $message->forceFill([
                'ai_result' => $classification,
                'work_order_id' => $this->positiveInt($classification['work_order_id'] ?? null),
                'intervention_id' => $this->positiveInt($classification['intervention_id'] ?? null),
            ])->save();

            if ($this->canAutoArchive($classification)) {
                $this->archiveAsWorkOrderActivity($message, $classification);
                $message->forceFill(['processing_status' => 'processed', 'processed_at' => now()])->save();

                return $message->refresh();
            }

            if ($this->shouldIgnore($classification)) {
                $message->forceFill(['processing_status' => 'processed', 'processed_at' => now()])->save();

                return $message->refresh();
            }

            $proposal = $this->createApprovalProposal($message, $conversation, $classification, $context);
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
                'error' => 'Nessun approvatore configurato per Email AI.',
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
        $action = $this->stringKeyedArray($classification['proposed_action'] ?? null);

        return (string) ($action['type'] ?? '') === 'none'
            && (bool) ($classification['requires_approval'] ?? false) === false;
    }

    /**
     * @param  array<string, mixed>  $classification
     */
    private function archiveAsWorkOrderActivity(EmailMessage $message, array $classification): void
    {
        $workOrderId = $this->positiveInt($classification['work_order_id'] ?? null);
        if ($workOrderId === null) {
            return;
        }

        $summary = trim((string) ($classification['summary'] ?? ''));
        $body = $summary !== '' ? $summary : trim((string) $message->body);

        $this->addWorkOrderActivity->execute(
            $workOrderId,
            'email',
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
    private function createApprovalProposal(EmailMessage $message, EmailConversation $conversation, array $classification, array $context): ?AiActionProposal
    {
        $approverId = $this->resolveApproverId();
        if ($approverId === null) {
            return null;
        }

        $thread = $this->threadFor($message, $conversation, $approverId, $context);
        $action = $this->stringKeyedArray($classification['proposed_action'] ?? null);
        $type = $this->proposalType($action);
        $payload = $this->stringKeyedArray($action['payload'] ?? null);
        $payload['email_message_id'] = (int) $message->id;
        $payload['source'] = 'email';

        $summary = trim((string) ($classification['summary'] ?? ''));
        if ($summary !== '' && ! isset($payload['body'])) {
            $payload['body'] = $summary;
        }

        $preview = trim((string) ($action['preview'] ?? ''));
        if ($preview === '') {
            $preview = $this->defaultPreview($type, $message, $classification);
        }

        try {
            return $this->createProposal->execute($thread, $approverId, $type, $payload, $preview);
        } catch (AiActionNotAllowedException $e) {
            // Rifiutata dalla policy: tipo non permesso o troppe proposte in
            // coda per l'approvatore. La mail resta processata e salvata; non
            // produce proposta, che è l'esito sicuro.
            Log::warning('Email proposal refused by AI policy', [
                'email_message_id' => $message->id,
                'type' => $e->actionType,
                'reason' => $e->reason,
            ]);

            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $action
     */
    private function proposalType(array $action): string
    {
        $type = (string) ($action['type'] ?? '');

        return in_array($type, [
            'append_work_order_activity_from_email',
            'create_intervention_from_email',
            'update_intervention_schedule_from_email',
        ], true) ? $type : 'append_work_order_activity_from_email';
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function threadFor(EmailMessage $message, EmailConversation $conversation, int $approverId, array $context): AiThread
    {
        /** @var AiThread|null $existing */
        $existing = AiThread::query()
            ->where('created_by_user_id', $approverId)
            ->where('panel_id', 'admin')
            ->where('meta->source', 'email')
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
                'source' => 'email',
                'conversation_id' => (int) $message->conversation_id,
                'email' => (string) $conversation->email,
            ],
        ]);

        $this->appendThreadMessage($thread, $message, $context);

        return $thread;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function appendThreadMessage(AiThread $thread, EmailMessage $message, array $context): void
    {
        AiMessage::query()->create([
            'ai_thread_id' => $thread->id,
            'user_id' => null,
            'role' => 'user',
            'content' => 'Email: '.trim(((string) $message->subject)."\n\n".(string) $message->body),
            'payload' => [
                'source' => 'email',
                'email_message_id' => (int) $message->id,
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
    private function defaultPreview(string $type, EmailMessage $message, array $classification): string
    {
        $summary = trim((string) ($classification['summary'] ?? ''));
        $summary = $summary !== '' ? $summary : mb_substr((string) $message->body, 0, 300);

        return match ($type) {
            'create_intervention_from_email' => 'Creare un intervento da email: '.$summary,
            'update_intervention_schedule_from_email' => 'Aggiornare la pianificazione intervento da email: '.$summary,
            default => 'Archiviare sulla commessa il messaggio email: '.$summary,
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
    private function stringKeyedArray(mixed $value): array
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
