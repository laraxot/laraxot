<?php

declare(strict_types=1);

use Illuminate\Testing\PendingCommand;
use PHPUnit\Framework\Assert;

uses(\Modules\WhatsApp\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;

afterEach(function (): void {
    CarbonImmutable::setTestNow();
});

it('warns when there is nothing to dismiss', function (): void {
    $pending = $this->artisan('whatsapp:mark-processed');

    Assert::assertInstanceOf(PendingCommand::class, $pending);

    $pending->expectsOutputToContain('Nessuna conversazione trovata.')
        ->assertExitCode(0);

});

it('marks the selected conversations as processed after confirmation', function (): void {
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-22 10:00:00'));

    $annoying = WhatsAppConversation::query()->create([
        'phone_e164' => '+393331230000',
        'status' => 'open',
        'last_message_at' => now(),
    ]);
    $message = WhatsAppMessage::query()->create([
        'conversation_id' => $annoying->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'ancora questo',
        'status' => 'received',
        'processing_status' => 'needs_review',
    ]);

    $pending = $this->artisan('whatsapp:mark-processed');


    Assert::assertInstanceOf(PendingCommand::class, $pending);


    $pending->expectsChoice(


    'Quali conversazioni vuoi segnare come elaborate?',


    [1],


    [1 => '1) +393331230000 [non approvata] — 22/08/2026 10:00 — ancora questo'],


    )
        ->expectsConfirmation('Segnare 1 conversazioni come elaborate? Nessun dato verra cancellato.', 'yes')
        ->assertExitCode(0);


    


    $message->refresh();


    expect($message->processing_status)->toBe('processed');


    


    $annoying->refresh();


    expect((int) $annoying->approved_up_to_message_id)->toBe($message->id);


    


    expect(WhatsAppConversation::query()->whereKey($annoying->id)->exists())->toBeTrue();


    });


    


    it('aborts without changing anything when the user declines confirmation', function (): void {


    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-22 10:00:00'));


    


    $conversation = WhatsAppConversation::query()->create([


    'phone_e164' => '+393339871234',


    'status' => 'open',


    'last_message_at' => now(),


    ]);


    


    $pending = $this->artisan('whatsapp:mark-processed');



    


    Assert::assertInstanceOf(PendingCommand::class, $pending);



    


    $pending->expectsChoice(


    'Quali conversazioni vuoi segnare come elaborate?',


    [1],


    [1 => '1) +393339871234 [non approvata] — 22/08/2026 10:00'],


    )
        ->expectsConfirmation('Segnare 1 conversazioni come elaborate? Nessun dato verra cancellato.', 'no')
        ->expectsOutputToContain('Operazione annullata.')
        ->assertExitCode(0);


    


    expect($conversation->fresh()?->approved_up_to_message_id)->toBeNull();


    });;;
