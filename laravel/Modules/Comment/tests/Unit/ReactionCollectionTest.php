<?php

declare(strict_types=1);

use Modules\Comment\Models\Collections\ReactionCollection;
use Modules\Comment\Models\Reaction;
use Modules\Comment\Tests\Support\ParityCommentatorStub;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('ReactionCollection summary returns reaction count and commentator_reacted parity Spatie', function (): void {
    $commentator = new ParityCommentatorStub(1);

    $reactionA = new Reaction(['reaction' => '👍', 'commentator_type' => 'parity-user', 'commentator_id' => 1]);
    $reactionB = new Reaction(['reaction' => '👍', 'commentator_type' => 'parity-user', 'commentator_id' => 2]);
    $reactionC = new Reaction(['reaction' => '❤️', 'commentator_type' => 'parity-user', 'commentator_id' => 1]);

    $collection = new ReactionCollection([$reactionA, $reactionB, $reactionC]);

    $summary = $collection->summary($commentator);

    Assert::assertCount(2, $summary);

    $thumbs = $summary->firstWhere('reaction', '👍');
    Assert::assertIsArray($thumbs);
    Assert::assertSame([
        'reaction' => '👍',
        'count' => 2,
        'commentator_reacted' => true,
    ], $thumbs);

    $heart = $summary->firstWhere('reaction', '❤️');
    Assert::assertIsArray($heart);
    Assert::assertSame([
        'reaction' => '❤️',
        'count' => 1,
        'commentator_reacted' => true,
    ], $heart);
});

test('Reaction model uses ReactionCollection as eloquent collection class', function (): void {
    Assert::assertInstanceOf(ReactionCollection::class, (new Reaction)->newCollection([]));
});
