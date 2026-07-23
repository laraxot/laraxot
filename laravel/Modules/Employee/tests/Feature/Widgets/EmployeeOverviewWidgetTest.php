<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Feature\Widgets;

use Modules\Employee\Filament\Widgets\EmployeeOverviewWidget;
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget;
use PHPUnit\Framework\Assert;

test('employee overview widget follows the Xot stats widget contract', function (): void {
    $widget = new EmployeeOverviewWidget();

    Assert::assertInstanceOf(XotBaseStatsOverviewWidget::class, $widget);
});
