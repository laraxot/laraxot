<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Enums;

use Modules\Notify\Enums\WhatsAppDriverEnum;
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
it('has correct cases', function (): void {
    Assert::assertCount(4, WhatsAppDriverEnum::cases());

    Assert::assertSame('twilio', WhatsAppDriverEnum::TWILIO->value);
    Assert::assertSame('messagebird', WhatsAppDriverEnum::MESSAGEBIRD->value);
    Assert::assertSame('vonage', WhatsAppDriverEnum::VONAGE->value);
    Assert::assertSame('infobip', WhatsAppDriverEnum::INFOBIP->value);
});

it('options returns correct array', function (): void {
    $options = WhatsAppDriverEnum::options();
    Assert::assertCount(4, $options);
    Assert::assertSame('Twilio', $options['twilio']);
    Assert::assertSame('MessageBird', $options['messagebird']);
    Assert::assertSame('Vonage', $options['vonage']);
    Assert::assertSame('Infobip', $options['infobip']);
});

it('labels returns localized array', function (): void {
    $labels = WhatsAppDriverEnum::labels();
    Assert::assertCount(4, $labels);
    Assert::assertArrayHasKey('twilio', $labels);
    Assert::assertArrayHasKey('messagebird', $labels);
    Assert::assertArrayHasKey('vonage', $labels);
    Assert::assertArrayHasKey('infobip', $labels);
});

it('is supported returns true for valid drivers', function (): void {
    Assert::assertTrue(WhatsAppDriverEnum::isSupported('twilio'));
    Assert::assertTrue(WhatsAppDriverEnum::isSupported('messagebird'));
    Assert::assertTrue(WhatsAppDriverEnum::isSupported('vonage'));
    Assert::assertTrue(WhatsAppDriverEnum::isSupported('infobip'));
});

it('is supported returns false for invalid drivers', function (): void {
    Assert::assertFalse(WhatsAppDriverEnum::isSupported('invalid'));
    Assert::assertFalse(WhatsAppDriverEnum::isSupported(''));
    Assert::assertFalse(WhatsAppDriverEnum::isSupported('TWILIO'));
    Assert::assertFalse(WhatsAppDriverEnum::isSupported('Twilio'));
});

it('get default returns default driver', function (): void {
    $default = WhatsAppDriverEnum::getDefault();

    Assert::assertInstanceOf(WhatsAppDriverEnum::class, $default);
    Assert::assertContains($default, WhatsAppDriverEnum::cases());
});

it('each case has unique value', function (): void {
    $values = array_map(static fn (WhatsAppDriverEnum $case): string => $case->value, WhatsAppDriverEnum::cases());
    $uniqueValues = array_unique($values);

    Assert::assertCount(count($values), $uniqueValues, 'All enum cases should have unique values');
});

it('cases returns all enum instances', function (): void {
    $cases = WhatsAppDriverEnum::cases();
    Assert::assertCount(4, $cases);

    foreach ($cases as $case) {
        Assert::assertInstanceOf(WhatsAppDriverEnum::class, $case);
    }
});

it('all cases expose non empty description', function (): void {
    foreach (WhatsAppDriverEnum::cases() as $case) {
        Assert::assertNotEmpty($case->getDescription());
    }
});
