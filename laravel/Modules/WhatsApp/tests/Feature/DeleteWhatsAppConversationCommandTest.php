<?php

declare(strict_types=1);

uses(\Modules\WhatsApp\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;

afterEach(function (): void {
    CarbonImmutable::setTestNow();
});

it('warns when there is nothing to delete', function (): void {
    $this->artisan('whatsapp:delete-conversation')
        ->expectsOutputToContain('Nessuna conversazione trovata.')
        ->assertExitCode(0);
});

it('deletes the selected conversations after confirmation', function (): void {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-22 10:00:00'));

    $spam = WhatsAppConversation::query()->create([
        'phone_e164' => '+393330001111',
        'status' => 'open',
        'last_message_at' => now(),
    ]);
    $spamMessage = WhatsAppMessage::query()->create([
        'conversation_id' => $spam->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'numero sbagliato',
        'status' => 'received',
        'processing_status' => 'processed',
    ]);

    // Sorts after $spam thanks to the earlier last_message_at.
    $legit = WhatsAppConversation::query()->create([
        'phone_e164' => '+393339990000',
        'status' => 'open',
        'last_message_at' => now()->subMinute(),
    ]);

    // Numbered 1, 2, 3... in display order — see PicksWhatsAppConversations.
    $this->artisan('whatsapp:delete-conversation')
        ->expectsChoice(
            'Quali conversazioni vuoi eliminare?',
            [1],
            [
                1 => '1) +393330001111 — 1 messaggi — numero sbagliato',
                2 => '2) +393339990000 — 0 messaggi',
            ],
        )
        ->expectsConfirmation('Eliminare 1 conversazioni e tutti i messaggi collegati? Azione irreversibile.', 'yes')
        ->assertExitCode(0);

    expect(WhatsAppConversation::query()->whereKey($spam->id)->exists())->toBeFalse()
        ->and(WhatsAppMessage::query()->whereKey($spamMessage->id)->exists())->toBeFalse()
        ->and(WhatsAppConversation::query()->whereKey($legit->id)->exists())->toBeTrue();
});

it('aborts without deleting when the user declines confirmation', function (): void {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-22 10:00:00'));

    $conversation = WhatsAppConversation::query()->create([
        'phone_e164' => '+393331239876',
        'status' => 'open',
        'last_message_at' => now(),
    ]);

    $this->artisan('whatsapp:delete-conversation')
        ->expectsChoice(
            'Quali conversazioni vuoi eliminare?',
            [1],
            [1 => '1) +393331239876 — 0 messaggi'],
        )
        ->expectsConfirmation('Eliminare 1 conversazioni e tutti i messaggi collegati? Azione irreversibile.', 'no')
        ->expectsOutputToContain('Operazione annullata.')
        ->assertExitCode(0);

    expect(WhatsAppConversation::query()->whereKey($conversation->id)->exists())->toBeTrue();
});
