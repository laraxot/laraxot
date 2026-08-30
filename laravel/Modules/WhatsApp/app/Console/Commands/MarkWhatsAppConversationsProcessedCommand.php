<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\WhatsApp\Actions\MarkWhatsAppConversationProcessedAction;
use Modules\WhatsApp\Console\Concerns\PicksWhatsAppConversations;
use Modules\WhatsApp\Models\WhatsAppConversation;

use function Laravel\Prompts\confirm;

/**
 * Lets an operator dismiss conversations that keep showing up as needing
 * attention but are not worth deleting: marks them as processed instead,
 * without touching any data. Alternative to `whatsapp:delete-conversation`
 * for the "annoying but legitimate" case.
 */
final class MarkWhatsAppConversationsProcessedCommand extends Command
{
    use PicksWhatsAppConversations;

    protected $signature = 'whatsapp:mark-processed {--phone= : Filtra per numero E.164}';

    protected $description = 'Segna una o piu conversazioni WhatsApp come elaborate, senza eliminarle.';

    public function handle(MarkWhatsAppConversationProcessedAction $markProcessed): int
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

        $conversationIds = $this->pickConversationIds($conversations, 'Quali conversazioni vuoi segnare come elaborate?');

        if ($conversationIds === []) {
            $this->info('Nessuna selezione, nessuna modifica.');

            return self::SUCCESS;
        }

        $confirmed = confirm(
            label: 'Segnare '.count($conversationIds).' conversazioni come elaborate? Nessun dato verra cancellato.',
            default: false,
        );

        if (! $confirmed) {
            $this->info('Operazione annullata.');

            return self::SUCCESS;
        }

        foreach ($conversationIds as $conversationId) {
            $markProcessed->execute($conversationId);
        }

        $this->info('Segnate come elaborate '.count($conversationIds).' conversazioni.');

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
