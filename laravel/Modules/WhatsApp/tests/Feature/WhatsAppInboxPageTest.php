<?php

declare(strict_types=1);

namespace Modules\WhatsApp\Tests\Feature;

use Carbon\CarbonImmutable;
use Livewire\Livewire;
use Modules\WhatsApp\Filament\Pages\WhatsAppInboxPage;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;

/*
 * Migrato da gestionale_commesse/tests/Feature/WhatsApp/WhatsAppInboxPageTest.php.
 * Namespace adattato: Modules\WhatsApp\Infrastructure\Models -> Modules\WhatsApp\Models.
 *
 * A differenza del sorgente, non autentichiamo un utente admin con feature flag abilitato:
 * WhatsAppInboxPage::canAccess()/shouldRegisterNavigation() dipendono da
 * App\Support\AccountFeatures::hasFeature() (shim che ritorna sempre il default) e da
 * Gate::allows('manage-whatsapp-ai') — sempre negato in questo ambiente. mount()/conversationList()/
 * thread() però NON invocano il gate, quindi il componente Livewire resta testabile direttamente.
 */
uses(TestCase::class);

function inboxPageConversationWithMessage(string $phone, string $body): WhatsAppConversation
{
    $conversation = WhatsAppConversation::query()->create([
        'phone_e164' => $phone,
        'status' => 'open',
        'last_message_at' => CarbonImmutable::now(),
    ]);

    WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => $body,
        'status' => 'received',
        'processing_status' => 'done',
    ]);

    return $conversation;
}

it('lists conversations on the inbox page', function (): void {
    inboxPageConversationWithMessage('+393201234567', 'Ciao, ho bisogno di assistenza');

    $list = whatsAppInboxComponent()->instance()->conversationList();

    expect($list)->toHaveCount(1)
        ->and($list[0]['last_message'])->toBe('Ciao, ho bisogno di assistenza');
});

it('selects a conversation and exposes its thread', function (): void {
    $conversation = inboxPageConversationWithMessage('+393209876543', 'Primo messaggio');

    $component = whatsAppInboxComponent()
        ->call('selectConversation', $conversation->id)
        ->assertSet('selectedConversationId', $conversation->id);

    $thread = $component->instance()->thread();

    expect($thread)->toHaveCount(1)
        ->and($thread[0]['body'])->toBe('Primo messaggio');
});

it('returns an empty thread when no conversation is selected', function (): void {
    expect(whatsAppInboxComponent()->instance()->thread())->toBe([]);
});
