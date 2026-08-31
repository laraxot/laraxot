<?php

declare(strict_types=1);

use Illuminate\Testing\PendingCommand;
use PHPUnit\Framework\Assert;

uses(\Modules\WhatsApp\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Queue;
use Modules\WhatsApp\Jobs\ProcessWhatsAppMessageJob;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;

afterEach(function (): void {
    CarbonImmutable::setTestNow();
});

it('warns when there is nothing to review at all', function (): void {
    $pending = $this->artisan('whatsapp:deapprove');

    Assert::assertInstanceOf(PendingCommand::class, $pending);

    $pending->expectsOutputToContain('Nessuna conversazione trovata.')
        ->assertExitCode(0);

});

it('lets the user pick which conversations to send back for review, regardless of their current state', function (): void {
    Queue::fake();
    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-22 10:00:00'));

    $approved = WhatsAppConversation::query()->create([
        'phone_e164' => '+393331112222',
        'display_name' => 'Mario Rossi',
        'status' => 'open',
        'last_message_at' => now(),
    ]);
    $approvedMessage = WhatsAppMessage::query()->create([
        'conversation_id' => $approved->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'tutto fatto, grazie',
        'status' => 'received',
        'processing_status' => 'processed',
    ]);
    $approved->forceFill(['approved_up_to_message_id' => $approvedMessage->id])->save();

    // Never approved (e.g. failed classification) — must still be selectable,
    // and sorts after $approved thanks to the earlier last_message_at.
    $neverApproved = WhatsAppConversation::query()->create([
        'phone_e164' => '+393339998888',
        'status' => 'open',
        'last_message_at' => now()->subMinute(),
        'approved_up_to_message_id' => null,
    ]);

    // The multiselect is numbered 1, 2, 3... in display order — never by raw
    // conversation id — precisely so typing "1" always means "first row
    // shown", even when Laravel Prompts falls back to Symfony's plain-text
    // ChoiceQuestion (e.g. proc_open disabled on shared hosting).
    $pending = $this->artisan('whatsapp:deapprove');

    Assert::assertInstanceOf(PendingCommand::class, $pending);

    $pending->expectsChoice(

    'Quali conversazioni vuoi rimettere in revisione?',

    [1],

    [

    1 => '1) Mario Rossi (+393331112222) [approvata] — 22/08/2026 10:00 — tutto fatto, grazie',

    2 => '2) +393339998888 [non approvata] — 22/08/2026 09:59',

    ],

    )
        ->expectsConfirmation('Rimettere in revisione 1 conversazioni? I messaggi verranno rianalizzati.', 'yes')
        ->assertExitCode(0);

    

    $approved->refresh();

    expect($approved->approved_up_to_message_id)->toBeNull();

    

    $approvedMessage->refresh();

    expect($approvedMessage->processing_status)->toBe('pending');

    

    Queue::assertPushed(ProcessWhatsAppMessageJob::class, fn (ProcessWhatsAppMessageJob $job): bool => $job->messageId === $approvedMessage->id);

    

    expect($neverApproved->fresh()?->approved_up_to_message_id)->toBeNull();

    });

    

    it('does nothing when nothing is selected', function (): void {

    Queue::fake();

    CarbonImmutable::setTestNow(CarbonImmutable::parse('2026-08-22 10:00:00'));

    

    $conversation = WhatsAppConversation::query()->create([

    'phone_e164' => '+393335556666',

    'status' => 'open',

    'last_message_at' => now(),

    ]);

    $message = WhatsAppMessage::query()->create([

    'conversation_id' => $conversation->id,

    'direction' => 'inbound',

    'message_type' => 'text',

    'body' => 'ok',

    'status' => 'received',

    'processing_status' => 'processed',

    ]);

    $conversation->forceFill(['approved_up_to_message_id' => $message->id])->save();

    

    $pending = $this->artisan('whatsapp:deapprove');


    

    Assert::assertInstanceOf(PendingCommand::class, $pending);


    

    $pending->expectsChoice(

    'Quali conversazioni vuoi rimettere in revisione?',

    [],

    [1 => '1) +393335556666 [approvata] — 22/08/2026 10:00 — ok'],

    )
        ->expectsOutputToContain('Nessuna selezione, nessuna modifica.')
        ->assertExitCode(0);

    

    Queue::assertNothingPushed();

    });;;
