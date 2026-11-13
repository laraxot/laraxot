<?php

declare(strict_types=1);
<<<<<<< .merge_file_h6MrLL

<<<<<<< HEAD
use Modules\Activity\Support\ActivityLogSchema;
=======
use Modules\Activity\Actions\Schema\IsActivityLogSchemaWritableAction;
>>>>>>> .merge_file_m06Afi
use Modules\Activity\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Activity\Tests\TestCase::class);
=======
use Modules\Activity\Actions\Schema\IsActivityLogSchemaWritableAction;
use Modules\Activity\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
>>>>>>> 13cf1831 (.)

test('returns false when activity log is disabled', function (): void {
    config(['activitylog.enabled' => false]);

<<<<<<< HEAD
    Assert::assertFalse(ActivityLogSchema::isWritable());
=======
    Assert::assertFalse(app(IsActivityLogSchemaWritableAction::class)->execute());
>>>>>>> 13cf1831 (.)
});
