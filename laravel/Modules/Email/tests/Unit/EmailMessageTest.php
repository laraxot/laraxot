<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Email\Database\Factories\EmailConversationFactory;
use Modules\Email\Database\Factories\EmailMessageFactory;
use Modules\Email\Models\EmailConversation;
use Modules\Email\Models\EmailMessage;
use Modules\Email\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

// No database writes here: factories are used unpersisted (makeOne()).

it('builds an email conversation with email/status stored directly on the model', function (): void {
    $conversation = EmailConversationFactory::new()->makeOne([
        'email' => 'customer@example.com',
        'status' => 'open',
    ]);

    Assert::assertInstanceOf(EmailConversation::class, $conversation);
    Assert::assertSame('customer@example.com', $conversation->email);
    Assert::assertSame('open', $conversation->status);
});

it('builds an email message with direction/status stored directly on the model', function (): void {
    $message = EmailMessageFactory::new()->makeOne([
        'direction' => 'inbound',
        'status' => 'received',
    ]);

    Assert::assertInstanceOf(EmailMessage::class, $message);
    Assert::assertSame('inbound', $message->direction);
    Assert::assertSame('received', $message->status);
});

it('exposes a belongsTo conversation relation on the message', function (): void {
    $message = new EmailMessage();

    Assert::assertInstanceOf(BelongsTo::class, $message->conversation());
});
