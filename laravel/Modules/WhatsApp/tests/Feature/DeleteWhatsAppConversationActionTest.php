<?php

declare(strict_types=1);

uses(\Modules\WhatsApp\Tests\TestCase::class);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Modules\AiAssistant\Actions\CreateAiActionProposalAction;
use Modules\AiAssistant\Models\AiThread;
use Modules\User\Database\Factories\UserFactory;
use Modules\WhatsApp\Actions\DeleteWhatsAppConversationAction;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;

it('deletes a conversation and cascades its messages', function (): void {
    $conversation = WhatsAppConversation::query()->create([
        'phone_e164' => '+393331112233',
        'status' => 'open',
        'last_message_at' => now(),
    ]);
    $message = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'spam',
        'status' => 'received',
        'processing_status' => 'processed',
    ]);

    app(DeleteWhatsAppConversationAction::class)->execute($conversation->id);

    expect(WhatsAppConversation::query()->whereKey($conversation->id)->exists())->toBeFalse()
        ->and(WhatsAppMessage::query()->whereKey($message->id)->exists())->toBeFalse();
});

it('clears the checkpoint pointers before deleting to avoid the circular foreign key', function (): void {
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
        'phone_e164' => '+393334445566',
        'status' => 'open',
        'last_message_at' => now(),
        'active_proposal_id' => $proposal->id,
    ]);
    $message = WhatsAppMessage::query()->create([
        'conversation_id' => $conversation->id,
        'direction' => 'inbound',
        'message_type' => 'text',
        'body' => 'test',
        'status' => 'received',
        'processing_status' => 'processed',
    ]);
    $conversation->forceFill(['approved_up_to_message_id' => $message->id])->save();

    app(DeleteWhatsAppConversationAction::class)->execute($conversation->id);

    expect(WhatsAppConversation::query()->whereKey($conversation->id)->exists())->toBeFalse()
        ->and(WhatsAppMessage::query()->whereKey($message->id)->exists())->toBeFalse();
});

it('throws when the conversation does not exist', function (): void {
    app(DeleteWhatsAppConversationAction::class)->execute(999999);
})->throws(ModelNotFoundException::class);
