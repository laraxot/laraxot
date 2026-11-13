<?php

declare(strict_types=1);

namespace Modules\Activity\Tests\Unit;

use Modules\Activity\Models\Snapshot;
use Modules\Activity\Tests\TestCase;
use PHPUnit\Framework\Assert;

<<<<<<< .merge_file_Pnhy8X
uses(\Modules\Activity\Tests\TestCase::class);
=======
uses(TestCase::class);
>>>>>>> .merge_file_fx4YS9

test('snapshot uses activity module connection', function (): void {
    $model = new Snapshot;

    Assert::assertSame('activity', $model->getConnectionName());
});
