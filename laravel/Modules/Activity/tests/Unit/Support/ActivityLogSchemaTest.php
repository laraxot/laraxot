<?php

declare(strict_types=1);

use Modules\Activity\Support\ActivityLogSchema;
use Modules\Activity\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Activity\Tests\TestCase::class);

test('returns false when activity log is disabled', function (): void {
    config(['activitylog.enabled' => false]);

    Assert::assertFalse(ActivityLogSchema::isWritable());
});
