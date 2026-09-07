<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Enums;

use Filament\Forms\Components\TextInput;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Modules\Notify\Enums\ContactTypeEnum;
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
    Assert::assertCount(6, ContactTypeEnum::cases());
    Assert::assertSame('phone', ContactTypeEnum::PHONE->value);
    Assert::assertSame('mobile', ContactTypeEnum::MOBILE->value);
    Assert::assertSame('email', ContactTypeEnum::EMAIL->value);
    Assert::assertSame('pec', ContactTypeEnum::PEC->value);
    Assert::assertSame('whatsapp', ContactTypeEnum::WHATSAPP->value);
    Assert::assertSame('fax', ContactTypeEnum::FAX->value);
});

it('implements filament contracts', function (): void {
    Assert::assertInstanceOf(HasLabel::class, ContactTypeEnum::PHONE);
    Assert::assertInstanceOf(HasIcon::class, ContactTypeEnum::PHONE);
    Assert::assertInstanceOf(HasColor::class, ContactTypeEnum::PHONE);
});

it('has trans trait', function (): void {
    $reflection = new \ReflectionClass(ContactTypeEnum::class);
    $traits = $reflection->getTraitNames();

    Assert::assertContains('Modules\\Xot\\Traits\\EnumTrait', $traits);
});

it('has required methods', function (): void {
    foreach (['getLabel', 'getIcon', 'getColor', 'getSearchable', 'getFormSchema'] as $method) {
        Assert::assertTrue(method_exists(ContactTypeEnum::PHONE, $method));
    }
});

it('getSearchable returns all values', function (): void {
    $searchable = ContactTypeEnum::getSearchable();
    Assert::assertCount(6, $searchable);

    foreach (['phone', 'mobile', 'email', 'pec', 'whatsapp', 'fax'] as $value) {
        Assert::assertContains($value, $searchable);
    }
});

it('getFormSchema returns TextInput components', function (): void {
    $schema = ContactTypeEnum::getFormSchema();
    Assert::assertCount(6, $schema);
    foreach ($schema as $component) {
        Assert::assertInstanceOf(TextInput::class, $component);
    }
});

it('each case has a unique value', function (): void {
    $values = array_map(static fn (ContactTypeEnum $case): string => $case->value, ContactTypeEnum::cases());
    $uniqueValues = array_unique($values);

    Assert::assertCount(count($values), $uniqueValues);
});
