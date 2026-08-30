<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Actions;

use Illuminate\Support\Facades\DB;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;

/**
 * Dismisses a conversation that is not worth deleting but keeps showing up
 * as needing attention: cancels its pending proposal, marks its unresolved
 * messages as processed, and advances the approval checkpoint to the last
 * message — without touching any data. The non-destructive alternative to
 * `DeleteWhatsAppConversationAction`.
 */
final readonly class MarkWhatsAppConversationProcessedAction
{
    public function execute(int $conversationId): void
    {
        DB::transaction(function () use ($conversationId): void {
            /** @var WhatsAppConversation $conversation */
            $conversation = WhatsAppConversation::query()->findOrFail($conversationId);

            if ($conversation->active_proposal_id !== null) {
                AiActionProposal::query()
                    ->whereKey($conversation->active_proposal_id)
                    ->where('status', 'pending')
                    ->update(['status' => 'cancelled']);
            }

            WhatsAppMessage::query()
                ->where('conversation_id', $conversation->id)
                ->whereIn('processing_status', ['pending', 'processing', 'needs_review', 'failed'])
                ->update([
                    'processing_status' => 'processed',
                    'processed_at' => now(),
                    'error' => null,
                ]);

            $lastMessageId = WhatsAppMessage::query()
                ->where('conversation_id', $conversation->id)
                ->max('id');

            $conversation->forceFill([
                'active_proposal_id' => null,
                'approved_up_to_message_id' => is_numeric($lastMessageId) ? (int) $lastMessageId : null,
            ])->save();
        });
    }
}
