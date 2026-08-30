<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\WhatsApp\Actions\ResetWhatsAppConversationApprovalAction;
use Modules\WhatsApp\Console\Concerns\PicksWhatsAppConversations;
use Modules\WhatsApp\Models\WhatsAppConversation;

use function Laravel\Prompts\confirm;

/**
 * Lets an operator pick which conversations — in any state, already
 * approved or not — to send back to "needs review" and re-run through the
 * AI, unlike `whatsapp:reprocess`, which always acts on all matching
 * conversations.
 */
final class DeapproveWhatsAppConversationsCommand extends Command
{
    use PicksWhatsAppConversations;

    protected $signature = 'whatsapp:deapprove {--phone= : Filtra per numero E.164} {--sync : Elabora subito, senza coda}';

    protected $description = 'Rivede le conversazioni WhatsApp, in qualsiasi stato, e rimette in revisione quelle scelte.';

    public function handle(ResetWhatsAppConversationApprovalAction $reset): int
    {
        $phone = $this->option('phone');

        /** @var Collection<int, WhatsAppConversation> $conversations */
        $conversations = WhatsAppConversation::query()
            ->when(is_string($phone) && $phone !== '', fn ($query) => $query->where('phone_e164', $phone))
            ->orderByDesc('last_message_at')
            ->get();

        if ($conversations->isEmpty()) {
            $this->warn('Nessuna conversazione trovata.');

            return self::SUCCESS;
        }

        $conversationIds = $this->pickConversationIds($conversations, 'Quali conversazioni vuoi rimettere in revisione?');

        if ($conversationIds === []) {
            $this->info('Nessuna selezione, nessuna modifica.');

            return self::SUCCESS;
        }

        $confirmed = confirm(
            label: 'Rimettere in revisione '.count($conversationIds).' conversazioni? I messaggi verranno rianalizzati.',
            default: false,
        );

        if (! $confirmed) {
            $this->info('Operazione annullata.');

            return self::SUCCESS;
        }

        $sync = (bool) $this->option('sync');
        $count = $reset->execute($conversationIds, $sync);

        $this->info("Rimessi da elaborare {$count} messaggi in ".count($conversationIds).' conversazioni.'
            .($sync ? ' Elaborati subito.' : ' Messi in coda (serve il queue worker).'));

        return self::SUCCESS;
    }

    private function label(WhatsAppConversation $conversation): string
    {
        $name = trim((string) $conversation->display_name);
        $phone = (string) $conversation->phone_e164;
        $identity = $name !== '' ? "{$name} ({$phone})" : $phone;
        $state = $conversation->approved_up_to_message_id !== null ? 'approvata' : 'non approvata';

        $lastMessage = $conversation->messages()->orderByDesc('id')->first();
        $preview = $lastMessage !== null ? Str::limit(trim((string) $lastMessage->body), 60) : '';
        $lastMessageAt = $conversation->last_message_at;
        $when = $lastMessageAt !== null ? Carbon::parse($lastMessageAt)->format('d/m/Y H:i') : '';

        return trim("{$identity} [{$state}] — {$when} — {$preview}", ' —');
    }
}
