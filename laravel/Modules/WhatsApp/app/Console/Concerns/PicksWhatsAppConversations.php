<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Console\Concerns;

use Illuminate\Support\Collection;
use Modules\WhatsApp\Models\WhatsAppConversation;

use function Laravel\Prompts\multiselect;

/**
 * Shows a multiselect numbered 1, 2, 3... in display order and maps the
 * answer back to conversation ids ourselves. Using real conversation ids as
 * the option keys is unsafe here: on hosts where `proc_open` is disabled
 * (common on shared hosting), Laravel Prompts silently falls back to
 * Symfony's plain-text ChoiceQuestion, and a typed number is matched
 * against the option key — so typing "1" would select whatever conversation
 * happens to have id 1, not the first row shown on screen.
 */
trait PicksWhatsAppConversations
{
    /**
     * @param  Collection<int, WhatsAppConversation>  $conversations
     * @return array<int, int>
     */
    private function pickConversationIds(Collection $conversations, string $promptLabel): array
    {
        $ordered = $conversations->values();

        $options = [];
        foreach ($ordered as $position => $conversation) {
            $options[$position + 1] = ($position + 1).') '.$this->label($conversation);
        }

        $selectedPositions = multiselect(label: $promptLabel, options: $options);

        return collect($selectedPositions)
            ->map(fn ($position): ?WhatsAppConversation => $ordered->get(((int) $position) - 1))
            ->filter()
            ->map(fn (WhatsAppConversation $conversation): int => (int) $conversation->id)
            ->values()
            ->all();
    }

    abstract private function label(WhatsAppConversation $conversation): string;
}
