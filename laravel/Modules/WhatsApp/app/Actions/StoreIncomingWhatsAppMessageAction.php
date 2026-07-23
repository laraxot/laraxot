<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Actions;

use Carbon\CarbonImmutable;
use Modules\WhatsApp\Jobs\ProcessWhatsAppMessageJob;
use Modules\WhatsApp\Support\PhoneNumberNormalizer;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;

final readonly class StoreIncomingWhatsAppMessageAction
{
    public function __construct(private PhoneNumberNormalizer $normalizer)
    {
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<int, WhatsAppMessage>
     */
    public function execute(array $payload): array
    {
        $stored = [];
        foreach ($this->extractMessages($payload) as $messagePayload) {
            $messageId = $this->stringValue($messagePayload['id'] ?? null);
            if ($messageId === '') {
                continue;
            }

            /** @var WhatsAppMessage|null $existing */
            $existing = WhatsAppMessage::query()->where('meta_message_id', $messageId)->first();
            if ($existing instanceof WhatsAppMessage) {
                $stored[] = $existing;
                continue;
            }

            $from = $this->normalizer->normalize($this->stringValue($messagePayload['from'] ?? null));
            if ($from === null) {
                continue;
            }

            $conversation = $this->conversationFor($from, $messagePayload);
            $receivedAt = $this->timestamp($messagePayload['timestamp'] ?? null);
            $messageType = $this->stringValue($messagePayload['type'] ?? 'text') ?: 'text';

            /** @var WhatsAppMessage $message */
            $message = WhatsAppMessage::query()->create([
                'conversation_id' => $conversation->id,
                'meta_message_id' => $messageId,
                'direction' => 'inbound',
                'from_number' => $from,
                'to_number' => $this->normalizer->normalize($this->stringValue($messagePayload['to'] ?? null) ?: (string) config('services.whatsapp.business_number', '')),
                'message_type' => $messageType,
                'body' => $this->messageBody($messagePayload, $messageType),
                'status' => 'received',
                'processing_status' => 'pending',
                'payload' => $messagePayload,
                'received_at' => $receivedAt,
            ]);

            $conversation->forceFill(['last_message_at' => $receivedAt ?? now()])->save();

            ProcessWhatsAppMessageJob::dispatch($message->id);
            $stored[] = $message;
        }

        return $stored;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<int, array<string, mixed>>
     */
    private function extractMessages(array $payload): array
    {
        $ycloudMessage = $this->extractYCloudMessage($payload);
        if ($ycloudMessage !== null) {
            return [$ycloudMessage];
        }

        $messages = [];
        $entries = is_array($payload['entry'] ?? null) ? $payload['entry'] : [];

        foreach ($entries as $entry) {
            if (! is_array($entry)) {
                continue;
            }

            $changes = is_array($entry['changes'] ?? null) ? $entry['changes'] : [];
            foreach ($changes as $change) {
                if (! is_array($change)) {
                    continue;
                }

                $value = is_array($change['value'] ?? null) ? $change['value'] : [];
                $contacts = is_array($value['contacts'] ?? null) ? $value['contacts'] : [];
                $contact = is_array($contacts[0] ?? null) ? $contacts[0] : [];
                $messageItems = is_array($value['messages'] ?? null) ? $value['messages'] : [];

                foreach ($messageItems as $message) {
                    if (! is_array($message)) {
                        continue;
                    }

                    $message['_contact'] = $contact;
                    $message['_metadata'] = is_array($value['metadata'] ?? null) ? $value['metadata'] : [];
                    $messages[] = $message;
                }
            }
        }

        return $messages;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>|null
     */
    private function extractYCloudMessage(array $payload): ?array
    {
        if (($payload['type'] ?? null) !== 'whatsapp.inbound_message.received') {
            return null;
        }

        $message = is_array($payload['whatsappInboundMessage'] ?? null)
            ? $payload['whatsappInboundMessage']
            : null;

        if ($message === null) {
            return null;
        }

        $profile = is_array($message['customerProfile'] ?? null) ? $message['customerProfile'] : [];

        return [
            'id' => $this->stringValue($message['wamid'] ?? null) ?: $this->stringValue($message['id'] ?? null),
            'from' => $this->stringValue($message['from'] ?? null),
            'to' => $this->stringValue($message['to'] ?? null),
            'timestamp' => $this->stringValue($message['sendTime'] ?? null),
            'type' => $this->stringValue($message['type'] ?? 'text') ?: 'text',
            'text' => is_array($message['text'] ?? null) ? $message['text'] : null,
            '_contact' => [
                'wa_id' => $this->stringValue($message['from'] ?? null),
                'profile' => [
                    'name' => $this->stringValue($profile['name'] ?? null),
                ],
            ],
            '_metadata' => [
                'provider' => 'ycloud',
                'event_id' => $this->stringValue($payload['id'] ?? null),
                'api_version' => $this->stringValue($payload['apiVersion'] ?? null),
                'waba_id' => $this->stringValue($message['wabaId'] ?? null),
                'from_user_id' => $this->stringValue($message['fromUserId'] ?? null),
                'from_parent_user_id' => $this->stringValue($message['fromParentUserId'] ?? null),
            ],
            '_raw_payload' => $payload,
        ];
    }

    /**
     * @param array<string, mixed> $messagePayload
     */
    private function conversationFor(string $from, array $messagePayload): WhatsAppConversation
    {
        $contact = is_array($messagePayload['_contact'] ?? null) ? $messagePayload['_contact'] : [];
        $profile = is_array($contact['profile'] ?? null) ? $contact['profile'] : [];

        /** @var WhatsAppConversation $conversation */
        $conversation = WhatsAppConversation::query()->firstOrCreate(
            ['phone_e164' => $from],
            [
                'wa_id' => $this->stringValue($contact['wa_id'] ?? null),
                'display_name' => $this->stringValue($profile['name'] ?? null),
                'status' => 'open',
            ],
        );

        $updates = [];
        $waId = $this->stringValue($contact['wa_id'] ?? null);
        if ($waId !== '' && $conversation->wa_id !== $waId) {
            $updates['wa_id'] = $waId;
        }

        $name = $this->stringValue($profile['name'] ?? null);
        if ($name !== '' && $conversation->display_name !== $name) {
            $updates['display_name'] = $name;
        }

        if ($updates !== []) {
            $conversation->forceFill($updates)->save();
        }

        return $conversation;
    }

    /**
     * @param array<string, mixed> $messagePayload
     */
    private function messageBody(array $messagePayload, string $messageType): ?string
    {
        if ($messageType === 'text' && is_array($messagePayload['text'] ?? null)) {
            return $this->stringValue($messagePayload['text']['body'] ?? null);
        }

        if ($messageType === 'button' && is_array($messagePayload['button'] ?? null)) {
            return $this->stringValue($messagePayload['button']['text'] ?? null);
        }

        if ($messageType === 'interactive' && is_array($messagePayload['interactive'] ?? null)) {
            return json_encode($messagePayload['interactive'], JSON_UNESCAPED_UNICODE) ?: null;
        }

        return null;
    }

    private function timestamp(mixed $value): ?CarbonImmutable
    {
        if (is_numeric($value)) {
            return CarbonImmutable::createFromTimestamp((int) $value);
        }

        if (is_string($value) && trim($value) !== '') {
            try {
                return CarbonImmutable::parse($value);
            } catch (\Throwable) {
                return null;
            }
        }

        return null;
    }

    private function stringValue(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }
}
