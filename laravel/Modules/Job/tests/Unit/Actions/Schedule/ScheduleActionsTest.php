<?php

declare(strict_types=1);

namespace Modules\Job\Tests\Unit\Actions\Schedule;

use Modules\Job\Actions\Schedule\ClearScheduleCacheAction;
use Modules\Job\Actions\Schedule\GetActiveSchedulesAction;
use Modules\Job\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

describe('Schedule Actions', function () {
    it('GetActiveSchedulesAction uses QueueableAction and has execute method', function () {
        $reflection = new \ReflectionClass(GetActiveSchedulesAction::class);
        Assert::assertTrue($reflection->isInstantiable());
        Assert::assertTrue($reflection->hasMethod('execute'));
        Assert::assertContains(\Spatie\QueueableAction\QueueableAction::class, $reflection->getTraitNames());
    });

    it('ClearScheduleCacheAction uses QueueableAction and has execute method', function () {
        $reflection = new \ReflectionClass(ClearScheduleCacheAction::class);
        Assert::assertTrue($reflection->isInstantiable());
        Assert::assertTrue($reflection->hasMethod('execute'));
        Assert::assertContains(\Spatie\QueueableAction\QueueableAction::class, $reflection->getTraitNames());
    });
});
