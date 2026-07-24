<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\ServiceProvider;
use Modules\WorkOrder\Providers\WorkOrderServiceProvider;
use Modules\WorkOrder\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('has WorkOrderServiceProvider', function (): void {
    Assert::assertInstanceOf(ServiceProvider::class, new WorkOrderServiceProvider(app()));
});
