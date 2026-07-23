<?php

declare(strict_types=1);

use Modules\Comment\Enums\NotificationSubscriptionType;
use Modules\Comment\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('NotificationSubscriptionType has english-backed string cases', function (): void {
    Assert::assertSame('participating', NotificationSubscriptionType::Participating->value);
    Assert::assertSame('all', NotificationSubscriptionType::All->value);
    Assert::assertSame('none', NotificationSubscriptionType::None->value);
});

test('NotificationSubscriptionType description uses comment lang keys', function (): void {
    Assert::assertNotSame('', NotificationSubscriptionType::Participating->description());
    Assert::assertNotSame('', NotificationSubscriptionType::All->description());
    Assert::assertNotSame('', NotificationSubscriptionType::None->description());
});

test('legacy italian enum file must not exist', function (): void {
    Assert::assertFileDoesNotExist(
        dirname(__DIR__, 2).'/app/Enums/TipoSottoscrizioneNotifica.php',
    );
});
