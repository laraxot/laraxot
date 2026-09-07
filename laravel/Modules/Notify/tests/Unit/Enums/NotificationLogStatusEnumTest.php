<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Enums;

use Modules\Notify\Enums\NotificationLogStatusEnum;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use PHPUnit\Framework\Assert;

>>>>>>> a988596b (first)
test('it exposes all expected statuses', function () {
    $values = array_map(static fn (NotificationLogStatusEnum $case): string => $case->value, NotificationLogStatusEnum::cases());

    Assert::assertSame([
        'pending',
        'sent',
        'delivered',
        'failed',
        'opened',
<<<<<<< HEAD
<<<<<<< HEAD
        'clicked'], $values);
=======
        'clicked',
    ], $values);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'clicked'], $values);
>>>>>>> a988596b (first)
});

test('it returns expected label color and icon', function () {
    foreach (NotificationLogStatusEnum::cases() as $case) {
        Assert::assertIsString($case->value);
        // EnumTrait: label/color/icon possono essere null se lang manca in test.
        $case->getLabel();
        $case->getColor();
        $case->getIcon();
    }
    Assert::assertCount(6, NotificationLogStatusEnum::cases());
});

test('it reports completed pending and failed states correctly', function () {
    Assert::assertTrue(NotificationLogStatusEnum::DELIVERED->isCompleted());
    Assert::assertTrue(NotificationLogStatusEnum::OPENED->isCompleted());
    Assert::assertTrue(NotificationLogStatusEnum::CLICKED->isCompleted());
    Assert::assertFalse(NotificationLogStatusEnum::SENT->isCompleted());
    Assert::assertTrue(NotificationLogStatusEnum::PENDING->isPending());
    Assert::assertTrue(NotificationLogStatusEnum::FAILED->isFailed());
});
