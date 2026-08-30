<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Modules\WhatsApp\Actions\ResetWhatsAppConversationApprovalAction;
use Modules\WhatsApp\Models\WhatsAppConversation;

/**
 * Re-marks WhatsApp messages as to-be-processed and re-runs the AI on them
 * (useful after enabling the LLM key or changing the classifier). Resets the
 * per-conversation dedup/checkpoint and cancels the stale pending proposals.
 */
final class ReprocessWhatsAppMessagesCommand extends Command
{
    protected $signature = 'whatsapp:reprocess {--phone= : Solo la conversazione con questo numero E.164} {--sync : Elabora subito, senza coda}';

    protected $description = 'Rimette da elaborare i messaggi WhatsApp e li rianalizza con l\'AI.';

    public function handle(ResetWhatsAppConversationApprovalAction $reset): int
    {
        $phone = $this->option('phone');

        /** @var Collection<int, WhatsAppConversation> $conversations */
        $conversations = WhatsAppConversation::query()
            ->when(is_string($phone) && $phone !== '', fn ($query) => $query->where('phone_e164', $phone))
            ->get();

        if ($conversations->isEmpty()) {
            $this->warn('Nessuna conversazione trovata.');

            return self::SUCCESS;
        }

        $conversationIds = [];
        foreach ($conversations as $conversation) {
            $conversationIds[] = (int) $conversation->id;
        }
        $sync = (bool) $this->option('sync');
        $count = $reset->execute($conversationIds, $sync);

        $this->info("Rimessi da elaborare {$count} messaggi in ".count($conversationIds).' conversazioni.'
            .($sync ? ' Elaborati subito.' : ' Messi in coda (serve il queue worker).'));

        return self::SUCCESS;
    }
}
