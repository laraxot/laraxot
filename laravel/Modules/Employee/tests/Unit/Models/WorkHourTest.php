<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Unit\Models;

use Modules\Employee\Enums\WorkHourStatusEnum;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Models\WorkHour;
use PHPUnit\Framework\Assert;

test('work hour casts enum and timestamp attributes', function (): void {
    $casts = (new WorkHour())->getCasts();

    Assert::assertSame('datetime', $casts['timestamp']);
    Assert::assertSame(WorkHourTypeEnum::class, $casts['type']);
    Assert::assertSame(WorkHourStatusEnum::class, $casts['status']);
});
