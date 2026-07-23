<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Feature;

use Modules\Employee\Filament\Widgets\EmployeeOverviewWidget;
use Modules\Employee\Filament\Widgets\TimeClockWidget;
use PHPUnit\Framework\Assert;

test('employee dashboard widgets are registered classes', function (): void {
    Assert::assertTrue(class_exists(EmployeeOverviewWidget::class));
    Assert::assertTrue(class_exists(TimeClockWidget::class));
});
