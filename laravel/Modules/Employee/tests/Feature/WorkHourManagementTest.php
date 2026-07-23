<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Feature;

use Modules\Employee\Models\WorkHour;
use PHPUnit\Framework\Assert;

test('work hour model exposes management columns', function (): void {
    $workHour = new WorkHour();

    Assert::assertSame('work_hours', $workHour->getTable());
    Assert::assertSame(
        [
            'employee_id',
            'type',
            'timestamp',
            'location_lat',
            'location_lng',
            'location_name',
            'device_info',
            'photo_path',
            'notes',
            'status',
            'approved_by',
            'approved_at',
        ],
        $workHour->getFillable(),
    );
});
