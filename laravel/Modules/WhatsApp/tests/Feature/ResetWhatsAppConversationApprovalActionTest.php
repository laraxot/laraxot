<?php

declare(strict_types=1);

uses(\Modules\WhatsApp\Tests\TestCase::class);

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Modules\AiAssistant\Actions\CreateAiActionProposalAction;
use Modules\AiAssistant\Models\AiThread;
use Modules\User\Database\Factories\UserFactory;
use Modules\WhatsApp\Actions\ResetWhatsAppConversationApprovalAction;
use Modules\WhatsApp\Jobs\ProcessWhatsAppMessageJob;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;

it('resets the approval checkpoint and rewinds inbound messages, queuing reprocessing', function (): void {
    Queue::fake();

    $user = UserFactory::new()->createOne();
    $thread = AiThread::query()->create([
        'public_id' => (string) Str::uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'admin',
        'last_message_at' => now(),
    ]);
    $proposal = app(CreateAiActionProposalAction::class)->execute(
        $thread,
        $user->id,
        'append_work_order_activity_from_whatsapp',
        [],
        'preview',
    );

    $conversation = WhatsAppConversation::query()->create([
        'phone_e164' => '+393331234567',
        'status' => 'open',
        'last_message_at' => now(),
        'active_proposal_id' => $proposal->id,
        'approved_up_to_message_id' => null,
    ]);

    $inbound = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'ciao, serve un intervento',
        'status' => 'received',
        'processing_status' => 'processed',
        'ai_action_proposal_id' => $proposal->id,
        'error' => null,
    ]);
    $outbound = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'outbound',
        'message_type' => 'text',
        'body' => 'ricevuto',
        'status' => 'sent',
        'processing_status' => 'processed',
    ]);
    $conversation->forceFill(['approved_up_to_message_id' => $inbound->id])->save();

    $count = app(ResetWhatsAppConversationApprovalAction::class)->execute([$conversation->id], sync: false);

    expect($count)->toBe(1);

    $conversation->refresh();
    expect($conversation->active_proposal_id)->toBeNull()
        ->and($conversation->approved_up_to_message_id)->toBeNull();

    $proposal->refresh();
    expect($proposal->status)->toBe('cancelled');

    $inbound->refresh();
    expect($inbound->processing_status)->toBe('pending')
        ->and($inbound->ai_action_proposal_id)->toBeNull()
        ->and($inbound->work_order_id)->toBeNull()
        ->and($inbound->intervention_id)->toBeNull();

    $outbound->refresh();
    expect($outbound->processing_status)->toBe('processed');

    Queue::assertPushed(ProcessWhatsAppMessageJob::class, fn (ProcessWhatsAppMessageJob $job): bool => $job->messageId === $inbound->id);
});

it('processes synchronously when sync is requested', function (): void {
    $conversation = WhatsAppConversation::query()->create([
        'phone_e164' => '+393339998877',
        'status' => 'open',
        'last_message_at' => now(),
    ]);
    $inbound = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'un messaggio',
        'status' => 'received',
        'processing_status' => 'processed',
    ]);

    $count = app(ResetWhatsAppConversationApprovalAction::class)->execute([$conversation->id], sync: true);

    expect($count)->toBe(1);

    $inbound->refresh();
    expect($inbound->processing_status)->not->toBe('pending');
});

it('returns 0 and touches nothing for an empty list of conversation ids', function (): void {
    Queue::fake();

    $count = app(ResetWhatsAppConversationApprovalAction::class)->execute([], sync: false);

    expect($count)->toBe(0);
    Queue::assertNothingPushed();
});
