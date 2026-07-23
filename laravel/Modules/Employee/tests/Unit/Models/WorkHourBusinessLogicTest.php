<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Unit\Models;

use Modules\Employee\Enums\WorkHourStatusEnum;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Models\WorkHour;
use PHPUnit\Framework\Assert;

test('work hour supports the declared type and status values', function (): void {
    Assert::assertSame(WorkHourTypeEnum::values(), WorkHour::TYPES);
    Assert::assertSame(
        [
            WorkHourStatusEnum::PENDING->value,
            WorkHourStatusEnum::APPROVED->value,
            WorkHourStatusEnum::REJECTED->value,
        ],
        WorkHour::STATUSES,
    );
});
