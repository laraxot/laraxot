<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Tests\Feature;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Queries\GetWhatsAppInboxQuery;
use Modules\WhatsApp\Tests\TestCase;

/*
 * Migrato da gestionale_commesse/tests/Feature/WhatsApp/GetWhatsAppInboxQueryTest.php.
 * Namespace adattato: Modules\WhatsApp\Application\Queries -> Modules\WhatsApp\Queries,
 * Modules\WhatsApp\Infrastructure\Models -> Modules\WhatsApp\Models (stessa API della query).
 */
uses(TestCase::class);

function inboxMakeConversation(array $overrides = []): WhatsAppConversation
{
    return WhatsAppConversation::query()->create(array_merge([
        'phone_e164' => '+39320'.random_int(1000000, 9999999),
        'status' => 'open',
        'last_message_at' => CarbonImmutable::now(),
    ], $overrides));
}

function inboxAddMessage(WhatsAppConversation $conversation, string $direction, string $body, CarbonImmutable $at): WhatsAppMessage
{
    $message = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => $direction,
        'message_type' => 'text',
        'body' => $body,
        'status' => 'received',
        'processing_status' => 'done',
    ]);

    // created_at drives ordering; set it explicitly.
    DB::table('whatsapp_messages')->where('id', $message->id)->update(['created_at' => $at]);

    return $message->refresh();
}

it('summarizes conversations ordered by recency with customer linkage', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Rossi Impianti S.r.l.', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);

    $older = inboxMakeConversation([
        'customer_id' => $customerId,
        'last_message_at' => CarbonImmutable::now()->subHours(2),
    ]);
    inboxAddMessage($older, 'inbound', 'Buongiorno, avrei un problema alla caldaia', CarbonImmutable::now()->subHours(3));
    inboxAddMessage($older, 'outbound', 'Le mandiamo un tecnico domani', CarbonImmutable::now()->subHours(2));

    $recent = inboxMakeConversation([
        'phone_e164' => '+393209999999',
        'last_message_at' => CarbonImmutable::now(),
    ]);
    inboxAddMessage($recent, 'inbound', 'Ciao', CarbonImmutable::now());

    $result = app(GetWhatsAppInboxQuery::class)->conversations();

    expect($result)->toHaveCount(2)
        ->and($result[0]['id'])->toBe($recent->id)
        ->and($result[0]['name'])->toBe('+393209999999')
        ->and($result[0]['is_linked'])->toBeFalse()
        ->and($result[0]['messages_count'])->toBe(1)
        ->and($result[1]['id'])->toBe($older->id)
        ->and($result[1]['name'])->toBe('Rossi Impianti S.r.l.')
        ->and($result[1]['is_linked'])->toBeTrue()
        ->and($result[1]['messages_count'])->toBe(2)
        ->and($result[1]['last_message'])->toBe('Le mandiamo un tecnico domani')
        ->and($result[1]['last_direction'])->toBe('outbound');
});

it('handles a conversation with no messages and no timestamp', function (): void {
    inboxMakeConversation(['phone_e164' => '+393201112222', 'last_message_at' => null]);

    $result = app(GetWhatsAppInboxQuery::class)->conversations();

    expect($result)->toHaveCount(1)
        ->and($result[0]['last_message'])->toBe('')
        ->and($result[0]['last_direction'])->toBe('')
        ->and($result[0]['messages_count'])->toBe(0)
        ->and($result[0]['last_at'])->toBeNull();
});

it('respects the conversations limit', function (): void {
    foreach (range(1, 4) as $i) {
        inboxMakeConversation([
            'phone_e164' => '+3932000000'.$i,
            'last_message_at' => CarbonImmutable::now()->subMinutes($i),
        ]);
    }

    expect(app(GetWhatsAppInboxQuery::class)->conversations(2))->toHaveCount(2);
});

it('returns the message thread of a conversation in chronological order', function (): void {
    $conversation = inboxMakeConversation();
    inboxAddMessage($conversation, 'inbound', 'Primo', CarbonImmutable::now()->subMinutes(10));
    inboxAddMessage($conversation, 'outbound', 'Secondo', CarbonImmutable::now()->subMinutes(5));

    $messages = app(GetWhatsAppInboxQuery::class)->messagesFor($conversation->id);

    expect($messages)->toHaveCount(2)
        ->and($messages[0]['body'])->toBe('Primo')
        ->and($messages[0]['direction'])->toBe('inbound')
        ->and($messages[1]['body'])->toBe('Secondo')
        ->and($messages[1]['direction'])->toBe('outbound')
        ->and($messages[1]['at'])->not->toBeNull();
});
