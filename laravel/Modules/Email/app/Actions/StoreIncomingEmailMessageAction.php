<?php

declare(strict_types=1);

namespace Modules\Email\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Modules\Email\Jobs\ProcessEmailMessageJob;
use Modules\Email\Models\EmailConversation;
use Modules\Email\Models\EmailMessage;
use Modules\Email\Settings\EmailSettings;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_match;

final class StoreIncomingEmailMessageAction
{
    use QueueableAction;

    public function __construct(private EmailSettings $settings) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function execute(array $payload): EmailMessage
    {
        $messageId = $this->stringValue($payload['message_id'] ?? $payload['Message-Id'] ?? null);
        if ($messageId === '') {
            $messageId = (string) Str::uuid();
        }

        /** @var EmailMessage|null $existing */
        $existing = EmailMessage::query()->where('external_message_id', $messageId)->first();
        if ($existing instanceof EmailMessage) {
            return $existing;
        }

        $direction = $this->direction($payload['direction'] ?? null);
        $fromEmail = $this->normalizeEmail($payload['from_email'] ?? $payload['from'] ?? null);
        if ($fromEmail === null) {
            throw new \InvalidArgumentException('Mittente email mancante o non valido.');
        }

        $toEmail = $this->normalizeEmail($payload['to_email'] ?? $payload['to'] ?? null) ?? $this->settings->businessEmail();
        $receivedAt = $this->timestamp($payload['received_at'] ?? $payload['timestamp'] ?? null) ?? CarbonImmutable::now();
        $conversationEmail = $direction === 'outbound' ? $toEmail : $fromEmail;
        if ($conversationEmail === '') {
            throw new \InvalidArgumentException('Destinatario email mancante o non valido.');
        }

        $conversation = $this->conversationFor($conversationEmail, $payload, $direction);

        /** @var EmailMessage $message */
        $message = EmailMessage::query()->create([
            'conversation_id' => $conversation->id,
            'external_message_id' => $messageId,
            'direction' => $direction,
            'from_email' => $fromEmail,
            'from_name' => $this->stringValue($payload['from_name'] ?? null),
            'to_email' => $toEmail !== '' ? $toEmail : null,
            'subject' => $this->stringValue($payload['subject'] ?? null),
            'body' => $this->messageBody($payload),
            'status' => $direction === 'outbound' ? 'sent' : 'received',
            'processing_status' => 'pending',
            'payload' => $payload,
            'received_at' => $direction === 'inbound' ? $receivedAt : null,
            'sent_at' => $direction === 'outbound' ? $receivedAt : null,
        ]);

        $conversation->forceFill(['last_message_at' => $receivedAt])->save();

        ProcessEmailMessageJob::dispatch($message->id);

        return $message;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function conversationFor(string $email, array $payload, string $direction): EmailConversation
    {
        /** @var EmailConversation $conversation */
        $conversation = EmailConversation::query()->firstOrCreate(
            ['email' => $email],
            [
                'display_name' => $direction === 'outbound' ? '' : $this->stringValue($payload['from_name'] ?? null),
                'status' => 'open',
            ],
        );

        $name = $this->stringValue($payload['from_name'] ?? null);
        if ($name !== '' && $conversation->display_name !== $name) {
            $conversation->forceFill(['display_name' => $name])->save();
        }

        return $conversation;
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    private function messageBody(array $payload): string
    {
        $body = $this->stringValue($payload['text'] ?? $payload['body'] ?? null);
        if ($body !== '') {
            return $body;
        }

        return trim(strip_tags($this->stringValue($payload['html'] ?? null)));
    }

    private function normalizeEmail(mixed $value): ?string
    {
        $email = strtolower($this->stringValue($value));
        if (is_string($value) && preg_match('/<([^>]+)>/', $value, $matches) === 1 && isset($matches[1])) {
            $email = strtolower(trim($matches[1]));
        }

        return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null;
    }

    private function direction(mixed $value): string
    {
        return $value === 'outbound' ? 'outbound' : 'inbound';
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
