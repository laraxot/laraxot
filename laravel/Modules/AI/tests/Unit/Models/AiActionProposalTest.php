<?php

declare(strict_types=1);

namespace Modules\AI\Tests\Unit\Models;

use Modules\AI\Models\AiActionProposal;
use PHPUnit\Framework\Assert;

test('ai action proposal casts attributes', function (): void {
    $casts = (new AiActionProposal())->getCasts();

    Assert::assertSame('array', $casts['payload']);
    Assert::assertSame('array', $casts['result']);
    Assert::assertSame('datetime', $casts['confirmed_at']);
    Assert::assertSame('datetime', $casts['executed_at']);
});

test('ai action proposal status constants are correct', function (): void {
    Assert::assertSame('pending', AiActionProposal::STATUS_PENDING);
    Assert::assertSame('cancelled', AiActionProposal::STATUS_CANCELLED);
    Assert::assertSame('confirmed', AiActionProposal::STATUS_CONFIRMED);
    Assert::assertSame('executed', AiActionProposal::STATUS_EXECUTED);
    Assert::assertSame('failed', AiActionProposal::STATUS_FAILED);
});
