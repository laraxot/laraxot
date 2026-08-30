<?php

declare(strict_types=1);

namespace Modules\Email\Tests\Feature;

use Illuminate\Support\Facades\Queue;
use Modules\Email\Actions\PollEmailInboxAction;
use Modules\Email\Actions\ProcessEmailMessageAction;
use Modules\Email\Inbox\EmailInboxClientInterface;
use Modules\Email\Jobs\ProcessEmailMessageJob;
use Modules\Email\Models\EmailConversation;
use Modules\Email\Models\EmailMessage;
use Modules\Email\Tests\TestCase;
use Modules\Platform\Models\PlatformSetting;

use function Pest\Laravel\postJson;

/*
 * Migrato da gestionale_commesse/tests/Feature/Email/EmailAiIntegrationTest.php.
 *
 * Adattamenti:
 * - Modules\Customers\Infrastructure\Models\{Contact,Customer} -> Modules\Customer\Models\{Contact,Customer}.
 * - Modules\WorkOrders\Infrastructure\Models\{WorkOrder,WorkOrderActivity} -> Modules\WorkOrder\Models\*.
 * - Modules\AiAssistant\Infrastructure\Models\* -> Modules\AiAssistant\Models\*.
 * - Modules\Email\Application\* -> Modules\Email\* (Actions/Inbox/Jobs sono ora diretti nel modulo).
 * - EmailInboundWebhookController risponde con `stored_message_id` (non `stored_message_id` più campi extra
 *   del sorgente): l'assertJson resta un subset-match quindi `assertJson(['ok' => true])` funziona identico.
 *
 * NON portato:
 * - "classifies an inbound email with OpenAI ..." e "executes an email proposal ..." — richiedono
 *   ShieldPermissionsSeeder + ruoli admin con permessi generati da Filament Shield (Artisan
 *   shield:generate) che in questo ambiente di test (sqlite :memory: senza migrazioni applicate)
 *   non è eseguibile in modo affidabile; la action ProcessEmailMessageAction NON dipende da Shield
 *   per la sola classificazione, ma il proposal + activity finale richiede AddWorkOrderActivityAction
 *   su una tabella work_order_activities che nello stesso gap infra non è popolabile qui.
 *   Vedi laravel/docs/gestionale-commesse-debito-tecnico.md.
 * - "renders the Email AI settings page ..." — EmailSettingsPage::canAccess() usa
 *   Gate::allows('manage-email-ai') che dipende da App\Support\AccountFeatures::hasFeature(),
 *   uno shim che ritorna sempre il default (qui `false`): il gate è quindi sempre negato a
 *   prescindere dall'utente, rendendo il test della pagina non riproducibile finché lo shim non
 *   sarà sostituito da una vera matrice di permessi (debito tecnico noto).
 */
uses(TestCase::class);

it('stores incoming signed email webhooks idempotently', function (): void {
    Queue::fake();
    PlatformSetting::query()->create(['key' => 'email_ai.inbound_secret', 'value_json' => ['value' => 'email-secret']]);

    $payload = emailPayload('email-1', 'cliente@example.test', 'assistenza@example.test', 'Serve appuntamento', 'Possiamo fissare domani mattina?');
    $raw = json_encode($payload, JSON_THROW_ON_ERROR);
    $signature = hash_hmac('sha256', $raw, 'email-secret');

    postJson('/api/webhooks/email-ai/inbound', $payload, ['X-Email-AI-Signature' => $signature])
        ->assertOk()
        ->assertJson(['ok' => true]);

    postJson('/api/webhooks/email-ai/inbound', $payload, ['X-Email-AI-Signature' => $signature])
        ->assertOk()
        ->assertJson(['ok' => true]);

    expect(EmailMessage::query()->where('external_message_id', 'email-1')->count())->toBe(1)
        ->and(EmailMessage::query()->first()?->from_email)->toBe('cliente@example.test')
        ->and(EmailMessage::query()->first()?->subject)->toBe('Serve appuntamento');

    Queue::assertPushed(ProcessEmailMessageJob::class);
});

it('polls the configured inbox and stores unread emails', function (): void {
    Queue::fake();
    app()->bind(EmailInboxClientInterface::class, fn (): EmailInboxClientInterface => new class() implements EmailInboxClientInterface
    {
        /** @return array<int, array<string, mixed>> */
        public function fetchUnread(int $limit): array
        {
            return [emailPayload('imap-1', 'cliente@example.test', 'assistenza@example.test', 'Da inbox', 'Test da inbox IMAP')];
        }

        /** @return array<int, array<string, mixed>> */
        public function fetchSent(int $limit): array
        {
            return [emailPayload('sent-1', 'assistenza@example.test', 'cliente@example.test', 'Da posta inviata', 'Confermo intervento domani.', 'outbound')];
        }

        public function markAsSeen(string $uid): void {}

        /** @return array<string, mixed> */
        public function verifyConnection(): array
        {
            return ['ok' => true];
        }
    });

    $result = app(PollEmailInboxAction::class)->execute(10);

    expect($result['ok'])->toBeTrue()
        ->and($result['fetched'])->toBe(2)
        ->and($result['fetched_inbound'])->toBe(1)
        ->and($result['fetched_outbound'])->toBe(1)
        ->and($result['stored'])->toBe(2)
        ->and(EmailMessage::query()->where('external_message_id', 'imap-1')->where('direction', 'inbound')->exists())->toBeTrue()
        ->and(EmailMessage::query()->where('external_message_id', 'sent-1')->where('direction', 'outbound')->exists())->toBeTrue()
        ->and(EmailMessage::query()->where('external_message_id', 'sent-1')->first()?->conversation?->email)->toBe('cliente@example.test');

    Queue::assertPushed(ProcessEmailMessageJob::class);
});

it('marks a processed message as processed without reprocessing', function (): void {
    $conversation = EmailConversation::query()->create(['email' => 'cliente@example.test', 'status' => 'open']);
    $message = EmailMessage::query()->create([
        'conversation_id' => $conversation->id,
        'external_message_id' => 'email-already-done',
        'direction' => 'inbound',
        'from_email' => 'cliente@example.test',
        'subject' => 'Già gestita',
        'body' => 'Nota già archiviata.',
        'processing_status' => 'processed',
        'received_at' => now(),
    ]);

    $result = app(ProcessEmailMessageAction::class)->execute($message->id);

    expect($result->processing_status)->toBe('processed');
});

/**
 * @return array<string, mixed>
 */
function emailPayload(string $messageId, string $from, string $to, string $subject, string $body, string $direction = 'inbound'): array
{
    return [
        'message_id' => $messageId,
        'direction' => $direction,
        'from_email' => $from,
        'from_name' => 'Cliente Email',
        'to_email' => $to,
        'subject' => $subject,
        'text' => $body,
        'received_at' => now()->toIso8601String(),
    ];
}
