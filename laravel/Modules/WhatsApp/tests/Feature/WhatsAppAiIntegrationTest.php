<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Tests\Feature;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Modules\AiAssistant\Actions\CreateAiActionProposalAction;
use Modules\AiAssistant\Actions\ExecuteAiActionProposalAction;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiThread;
use Modules\Platform\Models\PlatformSetting;
use Modules\WhatsApp\Actions\SendWhatsAppTextMessageAction;
use Modules\WhatsApp\Jobs\ProcessWhatsAppMessageJob;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;

use function Pest\Laravel\get;
use function Pest\Laravel\postJson;

/*
 * Migrato da gestionale_commesse/tests/Feature/WhatsApp/{WhatsAppAiIntegrationTest,
 * ExecuteOrganizeWhatsAppProposalTest}.php.
 *
 * Adattamenti:
 * - Modules\AiAssistant\Infrastructure\Models -> Modules\AiAssistant\Models.
 * - Modules\Customers\Infrastructure\Models -> Modules\Customer\Models.
 * - Modules\Platform\Infrastructure\Models -> Modules\Platform\Models.
 * - Modules\WhatsApp\Application\{Actions,Jobs} -> Modules\WhatsApp\{Actions,Jobs}.
 * - Modules\WorkOrders\Infrastructure\Models -> Modules\WorkOrder\Models.
 *
 * NON portato:
 * - "classifies an inbound WhatsApp message with OpenAI ..." (ProcessWhatsAppMessageAction end-to-end)
 *   e "renders the WhatsApp AI settings page ..." — stessa ragione documentata in
 *   Email/tests/Feature/EmailInboxActionsTest.php: dipendono da ShieldPermissionsSeeder/ruoli admin
 *   generati via Artisan shield:generate e/o da Gate::allows('manage-whatsapp-ai') sempre negato
 *   dallo shim App\Support\AccountFeatures. La action ExecuteAiActionProposalAction per i tipi
 *   "organize_whatsapp_request" e "append_work_order_activity_from_whatsapp" resta invece coperta.
 */
uses(TestCase::class);

it('verifies and stores incoming Meta webhook messages idempotently', function (): void {
    Queue::fake();
    PlatformSetting::query()->create(['key' => 'whatsapp.verify_token', 'value_json' => ['value' => 'verify-demo']]);
    PlatformSetting::query()->create(['key' => 'whatsapp.app_secret', 'value_json' => ['value' => 'secret-demo']]);

    get('/webhooks/whatsapp/meta?hub.mode=subscribe&hub.verify_token=verify-demo&hub.challenge=ok-123')
        ->assertOk()
        ->assertSee('ok-123');

    $payload = whatsappMetaPayload('wamid.demo-1', '393331234567', 'Serve appuntamento domani per la commessa Rossi');
    $raw = json_encode($payload, JSON_THROW_ON_ERROR);
    $signature = 'sha256='.hash_hmac('sha256', $raw, 'secret-demo');

    postJson('/webhooks/whatsapp/meta', $payload, ['X-Hub-Signature-256' => $signature])
        ->assertOk()
        ->assertJson(['ok' => true, 'stored_messages' => 1]);

    postJson('/webhooks/whatsapp/meta', $payload, ['X-Hub-Signature-256' => $signature])
        ->assertOk()
        ->assertJson(['ok' => true, 'stored_messages' => 1]);

    expect(WhatsAppMessage::query()->where('meta_message_id', 'wamid.demo-1')->count())->toBe(1);
    Queue::assertPushed(ProcessWhatsAppMessageJob::class);
});

it('stores incoming YCloud webhook messages idempotently', function (): void {
    Queue::fake();
    PlatformSetting::query()->create(['key' => 'whatsapp.ycloud_webhook_secret', 'value_json' => ['value' => 'ycloud-secret']]);

    $payload = whatsappYcloudPayload('evt_demo_1', 'wamid.ycloud-1', '+393331234567', '+3902123456', 'Messaggio dal telefono');
    $raw = json_encode($payload, JSON_THROW_ON_ERROR);
    $timestamp = (string) now()->timestamp;
    $signature = hash_hmac('sha256', $timestamp.'.'.$raw.'.', 'ycloud-secret');

    postJson('/webhooks/whatsapp/ycloud', $payload, ['YCloud-Signature' => "t={$timestamp},s={$signature}"])
        ->assertOk()
        ->assertJson(['ok' => true, 'provider' => 'ycloud', 'stored_messages' => 1]);

    postJson('/webhooks/whatsapp/ycloud', $payload, ['YCloud-Signature' => "t={$timestamp},s={$signature}"])
        ->assertOk()
        ->assertJson(['ok' => true, 'provider' => 'ycloud', 'stored_messages' => 1]);

    $message = WhatsAppMessage::query()->where('meta_message_id', 'wamid.ycloud-1')->first();
    expect($message)->toBeInstanceOf(WhatsAppMessage::class)
        ->and($message?->from_number)->toBe('+393331234567')
        ->and($message?->to_number)->toBe('+3902123456')
        ->and($message?->body)->toBe('Messaggio dal telefono')
        ->and(WhatsAppMessage::query()->where('meta_message_id', 'wamid.ycloud-1')->count())->toBe(1);

    Queue::assertPushed(ProcessWhatsAppMessageJob::class);
});

it('sends outbound WhatsApp messages and stores them locally', function (): void {
    PlatformSetting::query()->create(['key' => 'whatsapp.phone_number_id', 'value_json' => ['value' => 'phone-id']]);
    PlatformSetting::query()->create(['key' => 'whatsapp.access_token', 'value_json' => ['value' => 'meta-token']]);
    PlatformSetting::query()->create(['key' => 'whatsapp.business_number', 'value_json' => ['value' => '+3902123456']]);

    Http::fake([
        'https://graph.facebook.com/v20.0/phone-id/messages' => Http::response([
            'messages' => [['id' => 'wamid.out-1']],
        ], 200),
    ]);

    $message = app(SendWhatsAppTextMessageAction::class)->execute('+393331112222', 'Confermiamo appuntamento.');

    expect($message->direction)->toBe('outbound')
        ->and($message->meta_message_id)->toBe('wamid.out-1')
        ->and($message->processing_status)->toBe('processed');
});

it('sends outbound WhatsApp messages through YCloud when configured', function (): void {
    PlatformSetting::query()->create(['key' => 'whatsapp.provider', 'value_json' => ['value' => 'ycloud']]);
    PlatformSetting::query()->create(['key' => 'whatsapp.ycloud_api_key', 'value_json' => ['value' => 'ycloud-key']]);
    PlatformSetting::query()->create(['key' => 'whatsapp.business_number', 'value_json' => ['value' => '+3902123456']]);

    Http::fake([
        'https://api.ycloud.com/v2/whatsapp/messages/sendDirectly' => Http::response([
            'id' => 'ycloud-out-1',
            'wamid' => 'wamid.ycloud-out-1',
            'from' => '+3902123456',
            'to' => '+393331112222',
            'type' => 'text',
        ], 200),
    ]);

    $message = app(SendWhatsAppTextMessageAction::class)->execute('+393331112222', 'Confermiamo appuntamento.');

    expect($message->direction)->toBe('outbound')
        ->and($message->meta_message_id)->toBe('wamid.ycloud-out-1')
        ->and($message->processing_status)->toBe('processed');

    Http::assertSent(fn (Request $request): bool => $request->hasHeader('X-API-Key', 'ycloud-key')
        && $request['from'] === '+3902123456'
        && $request['to'] === '+393331112222'
        && $request['type'] === 'text'
        && data_get($request->data(), 'text.body') === 'Confermiamo appuntamento.');
});

it('executes an organize_whatsapp_request proposal creating customer, work order and intervention', function (): void {
    PlatformSetting::query()->updateOrCreate(['key' => 'interventions.work_mode'], ['value_json' => ['value' => 'work_order']]);
    \Modules\WorkOrder\Models\WorkOrderStatus::query()->firstOrCreate(['code' => 'in_progress'], ['name' => 'In lavorazione', 'color' => 'amber', 'is_terminal' => 0, 'sort_order' => 10]);

    $conversation = WhatsAppConversation::query()->create([
        'phone_e164' => '+393331234567',
        'display_name' => 'Rosa Bianchi',
        'status' => 'open',
        'last_message_at' => now(),
    ]);
    $message = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'sono la signora rosa, via de rossi 1, ho bisogno di aggiustare il lavandino',
        'status' => 'received',
        'processing_status' => 'needs_review',
    ]);

    $thread = AiThread::query()->create([
        'public_id' => (string) Str::uuid(),
        'created_by_user_id' => 1,
        'panel_id' => 'operator',
        'last_message_at' => now(),
    ]);

    $proposal = app(CreateAiActionProposalAction::class)->execute(
        $thread,
        1,
        'organize_whatsapp_request',
        [
            'whatsapp_message_id' => $message->id,
            'customer_name' => 'Rosa Bianchi',
            'address' => 'via de Rossi 1',
            'problem' => 'aggiustare il lavandino',
        ],
        'Nuova richiesta: aggiustare il lavandino',
    );

    $result = app(ExecuteAiActionProposalAction::class)->execute($proposal, 1);

    expect($result['ok'])->toBeTrue()
        ->and($result['customer_created'])->toBeTrue()
        ->and($result['work_order_id'])->not->toBeNull();

    expect(\Modules\Customer\Models\Customer::query()->where('legal_name', 'Rosa Bianchi')->exists())->toBeTrue()
        ->and(\Modules\WorkOrder\Models\WorkOrder::query()->whereKey($result['work_order_id'])->exists())->toBeTrue();

    $message->refresh();
    expect($message->processing_status)->toBe('processed')
        ->and((int) $message->work_order_id)->toBe($result['work_order_id'])
        ->and((int) $message->intervention_id)->toBe($result['intervention_id']);

    $proposal->refresh();
    expect($proposal->status)->toBe('executed');

    $conversation->refresh();
    expect((int) $conversation->approved_up_to_message_id)->toBe($message->id)
        ->and($conversation->active_proposal_id)->toBeNull();
});

it('fails gracefully when the proposal has no linked message', function (): void {
    $thread = AiThread::query()->create([
        'public_id' => (string) Str::uuid(),
        'created_by_user_id' => 1,
        'panel_id' => 'operator',
        'last_message_at' => now(),
    ]);

    $proposal = app(CreateAiActionProposalAction::class)->execute(
        $thread, 1, 'organize_whatsapp_request', ['customer_name' => 'X'], 'x',
    );

    $result = app(ExecuteAiActionProposalAction::class)->execute($proposal, 1);

    expect($result['ok'])->toBeFalse();
});

/**
 * @return array<string, mixed>
 */
function whatsappMetaPayload(string $messageId, string $from, string $body): array
{
    return [
        'object' => 'whatsapp_business_account',
        'entry' => [[
            'id' => 'business-id',
            'changes' => [[
                'field' => 'messages',
                'value' => [
                    'metadata' => ['phone_number_id' => 'phone-id'],
                    'contacts' => [[
                        'wa_id' => $from,
                        'profile' => ['name' => 'Cliente Demo'],
                    ]],
                    'messages' => [[
                        'from' => $from,
                        'id' => $messageId,
                        'timestamp' => (string) now()->timestamp,
                        'type' => 'text',
                        'text' => ['body' => $body],
                    ]],
                ],
            ]],
        ]],
    ];
}

/**
 * @return array<string, mixed>
 */
function whatsappYcloudPayload(string $eventId, string $wamid, string $from, string $to, string $body): array
{
    return [
        'id' => $eventId,
        'type' => 'whatsapp.inbound_message.received',
        'apiVersion' => 'v2',
        'createTime' => now()->toIso8601String(),
        'whatsappInboundMessage' => [
            'id' => 'ycloud-message-id',
            'wamid' => $wamid,
            'wabaId' => 'waba-id',
            'from' => $from,
            'fromUserId' => 'US.demo',
            'fromParentUserId' => 'US.parent',
            'customerProfile' => [
                'name' => 'Cliente YCloud',
                'username' => '@cliente',
            ],
            'to' => $to,
            'sendTime' => now()->toIso8601String(),
            'type' => 'text',
            'text' => ['body' => $body],
        ],
    ];
}
