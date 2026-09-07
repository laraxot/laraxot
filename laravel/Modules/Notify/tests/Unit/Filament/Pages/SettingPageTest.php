<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Filament\Pages;

use Modules\Notify\Filament\Pages\SettingPage;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
test('setting page returns env widget in header', function () {
    $page = new SettingPage;
=======
use PHPUnit\Framework\Assert;

test('setting page returns env widget in header', function () {
    $page = new SettingPage();
>>>>>>> a988596b (first)

    $widgets = $page->getHeaderWidgets();

    Assert::assertNotEmpty($widgets);
});
