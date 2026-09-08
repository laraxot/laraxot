<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Enums;

use Modules\Notify\Enums\NotificationLogStatusEnum;
use PHPUnit\Framework\Assert;

test('it exposes all expected statuses', function () {
    $values = array_map(static fn (NotificationLogStatusEnum $case): string => $case->value, NotificationLogStatusEnum::cases());

    Assert::assertSame([
        'pending',
        'sent',
        'delivered',
        'failed',
        'opened',
        'clicked'], $values);
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
