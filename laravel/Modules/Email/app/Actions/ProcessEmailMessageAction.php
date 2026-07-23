<?php

declare(strict_types=1);

namespace Modules\Email\Actions;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Modules\AiAssistant\Actions\CreateAiActionProposalAction;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiMessage;
use Modules\AiAssistant\Models\AiThread;
use Modules\Email\AI\EmailMessageClassifierInterface;
use Modules\Email\Settings\EmailSettings;
use Modules\Email\Models\EmailMessage;
use Modules\WorkOrder\Actions\AddWorkOrderActivityAction;
use Throwable;

final readonly class ProcessEmailMessageAction
{
    public function __construct(
        private ResolveEmailMessageContextAction $resolveContext,
        private EmailMessageClassifierInterface $classifier,
        private EmailSettings $settings,
        private AddWorkOrderActivityAction $addWorkOrderActivity,
        private CreateAiActionProposalAction $createProposal,
    ) {
    }

    public function execute(int $messageId): EmailMessage
    {
        /** @var EmailMessage $message */
        $message = EmailMessage::query()->with('conversation')->findOrFail($messageId);

        if ($message->processing_status === 'processed') {
            return $message;
        }

        $message->forceFill(['processing_status' => 'processing', 'error' => null])->save();

        try {
            $context = $this->resolveContext->execute($message->conversation);
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
     * @param array<string, mixed> $classification
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
     * @param array<string, mixed> $classification
     */
    private function shouldIgnore(array $classification): bool
    {
        $action = is_array($classification['proposed_action'] ?? null) ? $classification['proposed_action'] : [];

        return (string) ($action['type'] ?? '') === 'none'
            && (bool) ($classification['requires_approval'] ?? false) === false;
    }

    /**
     * @param array<string, mixed> $classification
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
            $message->received_at instanceof \Illuminate\Support\Carbon
                ? CarbonImmutable::instance($message->received_at)
                : CarbonImmutable::now(),
        );
    }

    /**
     * @param array<string, mixed> $classification
     * @param array<string, mixed> $context
     */
    private function createApprovalProposal(EmailMessage $message, array $classification, array $context): ?AiActionProposal
    {
        $approverId = $this->resolveApproverId();
        if ($approverId === null) {
            return null;
        }

        $thread = $this->threadFor($message, $approverId, $context);
        $action = is_array($classification['proposed_action'] ?? null) ? $classification['proposed_action'] : [];
        $type = $this->proposalType($action);
        $payload = is_array($action['payload'] ?? null) ? $action['payload'] : [];
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

        return $this->createProposal->execute($thread, $approverId, $type, $payload, $preview);
    }

    /**
     * @param array<string, mixed> $action
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
     * @param array<string, mixed> $context
     */
    private function threadFor(EmailMessage $message, int $approverId, array $context): AiThread
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
                'email' => (string) $message->conversation->email,
            ],
        ]);

        $this->appendThreadMessage($thread, $message, $context);

        return $thread;
    }

    /**
     * @param array<string, mixed> $context
     */
    private function appendThreadMessage(AiThread $thread, EmailMessage $message, array $context): void
    {
        AiMessage::query()->create([
            'ai_thread_id' => $thread->id,
            'user_id' => null,
            'role' => 'user',
            'content' => 'Email: ' . trim(((string) $message->subject) . "\n\n" . (string) $message->body),
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
     * @param array<string, mixed> $classification
     */
    private function defaultPreview(string $type, EmailMessage $message, array $classification): string
    {
        $summary = trim((string) ($classification['summary'] ?? ''));
        $summary = $summary !== '' ? $summary : mb_substr((string) $message->body, 0, 300);

        return match ($type) {
            'create_intervention_from_email' => 'Creare un intervento da email: ' . $summary,
            'update_intervention_schedule_from_email' => 'Aggiornare la pianificazione intervento da email: ' . $summary,
            default => 'Archiviare sulla commessa il messaggio email: ' . $summary,
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
}
