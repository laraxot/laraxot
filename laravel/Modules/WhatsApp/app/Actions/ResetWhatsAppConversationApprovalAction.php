<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Actions;

use Illuminate\Support\Collection;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\WhatsApp\Jobs\ProcessWhatsAppMessageJob;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;

/**
 * Resets the approval checkpoint on the given conversations and rewinds
 * their inbound messages to "pending" so the AI re-analyzes them from
 * scratch. Used by both `whatsapp:reprocess` (all/by-phone) and
 * `whatsapp:deapprove` (interactive selection).
 */
final readonly class ResetWhatsAppConversationApprovalAction
{
    /**
     * @param  array<int, int>  $conversationIds
     * @return int number of messages rewound
     */
    public function execute(array $conversationIds, bool $sync): int
    {
        if ($conversationIds === []) {
            return 0;
        }

        WhatsAppConversation::query()->whereIn('id', $conversationIds)
            ->update(['active_proposal_id' => null, 'approved_up_to_message_id' => null]);

        /** @var Collection<int, WhatsAppMessage> $messages */
        $messages = WhatsAppMessage::query()
            ->whereIn('conversation_id', $conversationIds)
            ->where('direction', 'inbound')
            ->orderBy('id')
            ->get();

        $proposalIds = $messages->pluck('ai_action_proposal_id')->filter()->unique()->values()->all();
        if ($proposalIds !== []) {
            AiActionProposal::query()->whereIn('id', $proposalIds)->where('status', 'pending')
                ->update(['status' => 'cancelled']);
        }

        $count = 0;

        foreach ($messages as $message) {
            $message->forceFill([
                'processing_status' => 'pending',
                'ai_action_proposal_id' => null,
                'work_order_id' => null,
                'intervention_id' => null,
                'error' => null,
            ])->save();

            if ($sync) {
                app(ProcessWhatsAppMessageAction::class)->execute((int) $message->id);
            } else {
                ProcessWhatsAppMessageJob::dispatch((int) $message->id);
            }

            $count++;
        }

        return $count;
    }
}
