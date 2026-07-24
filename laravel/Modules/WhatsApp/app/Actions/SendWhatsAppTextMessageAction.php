<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Actions;

use Modules\WhatsApp\Meta\WhatsAppClientInterface;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Settings\WhatsAppSettings;
use Modules\WhatsApp\Support\PhoneNumberNormalizer;
use Spatie\QueueableAction\QueueableAction;

final class SendWhatsAppTextMessageAction
{
    use QueueableAction;

    public function __construct(
        private WhatsAppClientInterface $client,
        private PhoneNumberNormalizer $normalizer,
        private WhatsAppSettings $settings,
    ) {}

    public function execute(string $toPhone, string $body): WhatsAppMessage
    {
        $to = $this->normalizer->normalize($toPhone);
        if ($to === null) {
            throw new \InvalidArgumentException('Numero WhatsApp destinatario non valido.');
        }

        $text = trim($body);
        if ($text === '') {
            throw new \InvalidArgumentException('Testo WhatsApp mancante.');
        }

        $response = $this->client->sendText($to, $text);
        $metaMessageId = data_get($response, 'messages.0.id')
            ?? data_get($response, 'wamid')
            ?? data_get($response, 'id');

        /** @var WhatsAppConversation $conversation */
        $conversation = WhatsAppConversation::query()->firstOrCreate(
            ['phone_e164' => $to],
            ['status' => 'open'],
        );

        /** @var WhatsAppMessage $message */
        $message = WhatsAppMessage::query()->create([
            'conversation_id' => $conversation->id,
            'meta_message_id' => is_string($metaMessageId) ? $metaMessageId : null,
            'direction' => 'outbound',
            'from_number' => $this->settings->businessNumber(),
            'to_number' => $to,
            'message_type' => 'text',
            'body' => $text,
            'status' => 'sent',
            'processing_status' => 'processed',
            'payload' => $response,
            'sent_at' => now(),
            'processed_at' => now(),
        ]);

        $conversation->forceFill(['last_message_at' => now()])->save();

        return $message;
    }
}
