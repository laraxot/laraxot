<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Unit\Models;

use Modules\Employee\Models\BaseModel;
use Modules\Xot\Models\XotBaseModel;
use PHPUnit\Framework\Assert;

test('employee base model extends the shared Xot base model', function (): void {
    $baseModel = new class extends BaseModel {};

    Assert::assertInstanceOf(XotBaseModel::class, $baseModel);
});
