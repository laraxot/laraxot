<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Console\Commands;

use Illuminate\Console\Command;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\WhatsApp\Actions\ProcessWhatsAppMessageAction;
use Modules\WhatsApp\Jobs\ProcessWhatsAppMessageJob;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;

/**
 * Re-marks WhatsApp messages as to-be-processed and re-runs the AI on them
 * (useful after enabling the LLM key or changing the classifier). Resets the
 * per-conversation dedup/checkpoint and cancels the stale pending proposals.
 */
final class ReprocessWhatsAppMessagesCommand extends Command
{
    protected $signature = 'whatsapp:reprocess {--phone= : Solo la conversazione con questo numero E.164} {--sync : Elabora subito, senza coda}';

    protected $description = 'Rimette da elaborare i messaggi WhatsApp e li rianalizza con l\'AI.';

    public function handle(): int
    {
        $phone = $this->option('phone');

        /** @var \Illuminate\Support\Collection<int, WhatsAppConversation> $conversations */
        $conversations = WhatsAppConversation::query()
            ->when(is_string($phone) && $phone !== '', fn ($query) => $query->where('phone_e164', $phone))
            ->get();

        if ($conversations->isEmpty()) {
            $this->warn('Nessuna conversazione trovata.');

            return self::SUCCESS;
        }

        $conversationIds = $conversations->pluck('id')->all();

        WhatsAppConversation::query()->whereIn('id', $conversationIds)
            ->update(['active_proposal_id' => null, 'approved_up_to_message_id' => null]);

        /** @var \Illuminate\Support\Collection<int, WhatsAppMessage> $messages */
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

        $sync = (bool) $this->option('sync');
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

        $this->info("Rimessi da elaborare {$count} messaggi in ".count($conversationIds).' conversazioni.'
            .($sync ? ' Elaborati subito.' : ' Messi in coda (serve il queue worker).'));

        return self::SUCCESS;
    }
}
