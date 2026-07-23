<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Feature;

use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Models\WorkHour;
use PHPUnit\Framework\Assert;

test('work hour next-action cycle uses supported entry types', function (): void {
    Assert::assertSame(
        [
            WorkHourTypeEnum::CLOCK_IN->value,
            WorkHourTypeEnum::CLOCK_OUT->value,
            WorkHourTypeEnum::BREAK_START->value,
            WorkHourTypeEnum::BREAK_END->value,
        ],
        WorkHour::TYPES,
    );
});
