<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Unit;

use Modules\Employee\Models\Employee;
use Modules\Employee\Models\WorkHour;
use PHPUnit\Framework\Assert;

test('employee module exposes its core models', function (): void {
    Assert::assertTrue(class_exists(Employee::class));
    Assert::assertTrue(class_exists(WorkHour::class));
});
