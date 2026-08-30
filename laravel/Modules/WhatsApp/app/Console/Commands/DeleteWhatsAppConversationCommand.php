<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Modules\WhatsApp\Actions\DeleteWhatsAppConversationAction;
use Modules\WhatsApp\Console\Concerns\PicksWhatsAppConversations;
use Modules\WhatsApp\Models\WhatsAppConversation;

use function Laravel\Prompts\confirm;

/**
 * Lets an operator pick non-approvable conversations (spam, wrong number,
 * tests) and delete them, together with all their messages.
 */
final class DeleteWhatsAppConversationCommand extends Command
{
    use PicksWhatsAppConversations;

    protected $signature = 'whatsapp:delete-conversation {--phone= : Filtra per numero E.164}';

    protected $description = 'Elimina definitivamente una o piu conversazioni WhatsApp e i relativi messaggi.';

    public function handle(DeleteWhatsAppConversationAction $delete): int
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

        $conversationIds = $this->pickConversationIds($conversations, 'Quali conversazioni vuoi eliminare?');

        if ($conversationIds === []) {
            $this->info('Nessuna selezione, nessuna modifica.');

            return self::SUCCESS;
        }

        $confirmed = confirm(
            label: 'Eliminare '.count($conversationIds).' conversazioni e tutti i messaggi collegati? Azione irreversibile.',
            default: false,
        );

        if (! $confirmed) {
            $this->info('Operazione annullata.');

            return self::SUCCESS;
        }

        foreach ($conversationIds as $conversationId) {
            $delete->execute($conversationId);
        }

        $this->info('Eliminate '.count($conversationIds).' conversazioni.');

        return self::SUCCESS;
    }

    private function label(WhatsAppConversation $conversation): string
    {
        $name = trim((string) $conversation->display_name);
        $phone = (string) $conversation->phone_e164;
        $identity = $name !== '' ? "{$name} ({$phone})" : $phone;

        $lastMessage = $conversation->messages()->orderByDesc('id')->first();
        $preview = $lastMessage !== null ? Str::limit(trim((string) $lastMessage->body), 60) : '';
        $count = $conversation->messages()->count();

        return trim("{$identity} — {$count} messaggi — {$preview}", ' —');
    }
}
