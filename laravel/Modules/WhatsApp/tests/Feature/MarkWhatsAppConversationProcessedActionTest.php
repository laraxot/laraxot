<?php

declare(strict_types=1);

uses(\Modules\WhatsApp\Tests\TestCase::class);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Modules\AiAssistant\Actions\CreateAiActionProposalAction;
use Modules\AiAssistant\Models\AiThread;
use Modules\User\Database\Factories\UserFactory;
use Modules\WhatsApp\Actions\MarkWhatsAppConversationProcessedAction;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;

it('cancels the pending proposal, marks unresolved messages processed, and advances the checkpoint', function (): void {
    $user = UserFactory::new()->createOne();
    $thread = AiThread::query()->create([
        'public_id' => (string) Str::uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'admin',
        'last_message_at' => now(),
    ]);
    $proposal = app(CreateAiActionProposalAction::class)->execute(
        $thread, $user->id, 'append_work_order_activity_from_whatsapp', [], 'preview',
    );

    $conversation = WhatsAppConversation::query()->create([
        'phone_e164' => '+393337778888',
        'status' => 'open',
        'last_message_at' => now(),
        'active_proposal_id' => $proposal->id,
    ]);

    $needsReview = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'una richiesta fastidiosa',
        'status' => 'received',
        'processing_status' => 'needs_review',
        'ai_action_proposal_id' => $proposal->id,
        'error' => 'qualcosa',
    ]);
    $alreadyProcessed = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'gia gestito prima',
        'status' => 'received',
        'processing_status' => 'processed',
        'processed_at' => now()->subDay(),
    ]);

    app(MarkWhatsAppConversationProcessedAction::class)->execute($conversation->id);

    $proposal->refresh();
    expect($proposal->status)->toBe('cancelled');

    $needsReview->refresh();
    expect($needsReview->processing_status)->toBe('processed')
        ->and($needsReview->error)->toBeNull()
        ->and($needsReview->processed_at)->not->toBeNull();

    $conversation->refresh();
    expect($conversation->active_proposal_id)->toBeNull()
        ->and((int) $conversation->approved_up_to_message_id)->toBe($alreadyProcessed->id);
});

it('is a no-op safe to call on a conversation with no pending proposal or unresolved messages', function (): void {
    $conversation = WhatsAppConversation::query()->create([
        'phone_e164' => '+393339990000',
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

    app(MarkWhatsAppConversationProcessedAction::class)->execute($conversation->id);

    $conversation->refresh();
    expect($conversation->active_proposal_id)->toBeNull()
        ->and((int) $conversation->approved_up_to_message_id)->toBe($message->id);
});

it('throws when the conversation does not exist', function (): void {
    app(MarkWhatsAppConversationProcessedAction::class)->execute(999999);
})->throws(ModelNotFoundException::class);
