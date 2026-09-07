<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Filament\Forms\Components;

use Filament\Forms\Components\TextInput;
use Modules\Notify\Filament\Forms\Components\ChannelCheckboxList;
use Modules\Notify\Filament\Forms\Components\HtmlLayoutPathSelect;
use Modules\Notify\Filament\Forms\Components\MailTemplateSelect;
use Modules\Notify\Tests\Fixtures\ContactSectionTestProxy;
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
test('channel checkbox list and selects have expected default names', function () {
    $channels = ChannelCheckboxList::make();
    $mailTemplate = MailTemplateSelect::make();

    Assert::assertSame('channels', $channels->getName());
    Assert::assertSame('mail_template_slug', $mailTemplate->getName());
});

test('html layout path select exposes expected default name via method signature', function () {
    $reflection = new \ReflectionMethod(HtmlLayoutPathSelect::class, 'make');
    $params = $reflection->getParameters();

    Assert::assertNotEmpty($params);
});

test('contact section returns text inputs schema from enum', function () {
<<<<<<< HEAD
    $proxy = new ContactSectionTestProxy;
=======
    $proxy = new ContactSectionTestProxy();
>>>>>>> a988596b (first)
    $schema = $proxy->exposedFormSchema();
    foreach ($schema as $component) {
        Assert::assertInstanceOf(TextInput::class, $component);
    }
});
