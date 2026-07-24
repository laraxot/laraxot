<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Queries;

use Carbon\CarbonInterface;
use Illuminate\Support\Str;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;

/**
 * Read model for the WhatsApp inbox: conversation summaries ordered by recency
 * and the message thread of a single conversation. This is how incoming
 * WhatsApp messages get "organized" in the gestionale — grouped per contact and
 * flagged when not yet linked to a customer.
 */
final class GetWhatsAppInboxQuery
{
    /**
     * @return list<array{id: int, name: string, phone: string, status: string, is_linked: bool, messages_count: int, last_message: string, last_direction: string, last_at: string|null}>
     */
    public function conversations(int $limit = 50): array
    {
        return array_values(
            WhatsAppConversation::query()
                ->with('customer')
                ->withCount('messages')
                ->orderByDesc('last_message_at')
                ->orderByDesc('id')
                ->limit(max(1, $limit))
                ->get()
                ->map(fn (WhatsAppConversation $conversation): array => $this->summarize($conversation))
                ->all()
        );
    }

    /**
     * @return list<array{id: int, direction: string, body: string, status: string, at: string|null}>
     */
    public function messagesFor(int $conversationId, int $limit = 100): array
    {
        return array_values(
            WhatsAppMessage::query()
                ->where('conversation_id', $conversationId)
                ->orderBy('created_at')
                ->orderBy('id')
                ->limit(max(1, $limit))
                ->get()
                ->map(fn (WhatsAppMessage $message): array => [
                    'id' => (int) $message->id,
                    'direction' => (string) $message->direction,
                    'body' => trim((string) $message->body),
                    'status' => (string) $message->status,
                    'at' => $this->formatTimestamp($message->created_at),
                ])
                ->all()
        );
    }

    /**
     * @return array{id: int, name: string, phone: string, status: string, is_linked: bool, messages_count: int, last_message: string, last_direction: string, last_at: string|null}
     */
    private function summarize(WhatsAppConversation $conversation): array
    {
        /** @var WhatsAppMessage|null $last */
        $last = $conversation->messages()->latest('created_at')->latest('id')->first();

        $phone = (string) $conversation->phone_e164;
        $name = $conversation->customer?->legal_name;

        return [
            'id' => (int) $conversation->id,
            'name' => $name !== null && $name !== '' ? $name : $phone,
            'phone' => $phone,
            'status' => (string) $conversation->status,
            'is_linked' => $conversation->customer_id !== null,
            'messages_count' => (int) ($conversation->messages_count ?? 0),
            'last_message' => $last !== null ? Str::limit(trim((string) $last->body), 80) : '',
            'last_direction' => $last !== null ? (string) $last->direction : '',
            'last_at' => $this->formatTimestamp($conversation->last_message_at),
        ];
    }

    private function formatTimestamp(mixed $value): ?string
    {
        return $value instanceof CarbonInterface
            ? $value->format('d/m/Y H:i')
            : null;
    }
}
