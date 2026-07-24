<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Database\Eloquent\Model;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderActivity;
use Modules\WorkOrder\Models\WorkOrderAssignment;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Models\WorkOrderStatusEvent;
use Modules\WorkOrder\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

it('has WorkOrder model', function (): void {
    Assert::assertInstanceOf(Model::class, new WorkOrder());
});

it('has WorkOrderStatus model', function (): void {
    Assert::assertInstanceOf(Model::class, new WorkOrderStatus());
});

it('has WorkOrderStatusEvent model', function (): void {
    Assert::assertInstanceOf(Model::class, new WorkOrderStatusEvent());
});

it('has WorkOrderActivity model', function (): void {
    Assert::assertInstanceOf(Model::class, new WorkOrderActivity());
});

it('has WorkOrderAssignment model', function (): void {
    Assert::assertInstanceOf(Model::class, new WorkOrderAssignment());
});
