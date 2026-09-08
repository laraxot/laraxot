<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Filament\Pages;

use Modules\Notify\Filament\Pages\SettingPage;
use PHPUnit\Framework\Assert;

test('setting page returns env widget in header', function () {
    $page = new SettingPage;

    $widgets = $page->getHeaderWidgets();

    Assert::assertNotEmpty($widgets);
});
