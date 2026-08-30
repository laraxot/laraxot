<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Actions;

use Illuminate\Support\Facades\DB;
use Modules\WhatsApp\Models\WhatsAppConversation;

/**
 * Permanently deletes a WhatsApp conversation that turned out to be
 * unusable (spam, wrong number, test). Its messages cascade-delete via the
 * `whatsapp_messages.conversation_id` foreign key.
 */
final readonly class DeleteWhatsAppConversationAction
{
    public function execute(int $conversationId): void
    {
        DB::transaction(function () use ($conversationId): void {
            /** @var WhatsAppConversation $conversation */
            $conversation = WhatsAppConversation::query()->findOrFail($conversationId);

            // Clear the checkpoint pointers first: they reference the very
            // messages that are about to cascade-delete with the conversation.
            $conversation->forceFill([
                'active_proposal_id' => null,
                'approved_up_to_message_id' => null,
            ])->save();

            $conversation->delete();
        });
    }
}
