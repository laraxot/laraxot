<?php

declare(strict_types=1);

use Modules\Activity\Actions\Schema\IsActivityLogSchemaWritableAction;
use Modules\Activity\Tests\TestCase;
use PHPUnit\Framework\Assert;

<<<<<<< .merge_file_xyM9vM
uses(\Modules\Activity\Tests\TestCase::class);
=======
<<<<<<< .merge_file_ihCkvq
uses(TestCase::class);
=======
uses(\Modules\Activity\Tests\TestCase::class);
>>>>>>> .merge_file_nyeCL6
>>>>>>> .merge_file_KErboF

it('returns false when activity log is disabled', function (): void {
    config(['activitylog.enabled' => false]);

    Assert::assertFalse(app(IsActivityLogSchemaWritableAction::class)->execute());
});
