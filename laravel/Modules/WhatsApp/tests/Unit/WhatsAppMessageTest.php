<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\WhatsApp\Database\Factories\WhatsAppConversationFactory;
use Modules\WhatsApp\Database\Factories\WhatsAppMessageFactory;
use Modules\WhatsApp\Models\WhatsAppConversation;
use Modules\WhatsApp\Models\WhatsAppMessage;
use Modules\WhatsApp\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\WhatsApp\Tests\TestCase::class);

// No database writes here: factories are used unpersisted (makeOne()).

it('builds a whatsapp conversation with phone/status stored directly on the model', function (): void {
    $conversation = WhatsAppConversationFactory::new()->makeOne([
        'phone_e164' => '+393331234567',
        'status' => 'open',
    ]);

    Assert::assertInstanceOf(WhatsAppConversation::class, $conversation);
    Assert::assertSame('+393331234567', $conversation->phone_e164);
    Assert::assertSame('open', $conversation->status);
});

it('builds a whatsapp message with direction/status stored directly on the model', function (): void {
    $message = WhatsAppMessageFactory::new()->makeOne([
        'direction' => 'inbound',
        'status' => 'received',
    ]);

    Assert::assertInstanceOf(WhatsAppMessage::class, $message);
    Assert::assertSame('inbound', $message->direction);
    Assert::assertSame('received', $message->status);
});

it('exposes a belongsTo conversation relation on the message', function (): void {
    $message = new WhatsAppMessage();

    Assert::assertInstanceOf(BelongsTo::class, $message->conversation());
});
