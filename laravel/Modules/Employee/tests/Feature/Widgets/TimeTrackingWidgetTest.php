<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Feature\Widgets;

use Modules\Employee\Filament\Widgets\TimeClockWidget;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use PHPUnit\Framework\Assert;
use ReflectionClass;

test('time clock widget is the supported time tracking widget', function (): void {
    $reflection = new ReflectionClass(TimeClockWidget::class);

    Assert::assertTrue($reflection->isSubclassOf(XotBaseSchemaWidget::class));
    Assert::assertTrue($reflection->hasMethod('clockIn'));
    Assert::assertTrue($reflection->hasMethod('clockOut'));
});
