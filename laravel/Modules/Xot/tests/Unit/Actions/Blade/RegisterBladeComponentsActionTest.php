<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\Actions\Blade;

use Mockery\MockInterface;
use Modules\Xot\Actions\Blade\RegisterBladeComponentsAction;
use Modules\Xot\Actions\File\GetComponentsAction;
use Modules\Xot\Datas\ComponentFileData;
<<<<<<< HEAD
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\DataCollection;

uses(TestCase::class);

=======
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\DataCollection;

>>>>>>> 77b1e2748 (first)
it('registers blade components correctly', function (): void {
    $path = 'some/path';
    $namespace = 'Some\\Namespace';
    $prefix = 'prefix';

    /** @var DataCollection<int, ComponentFileData> $mockComps */
    $mockComps = ComponentFileData::collection([
        [
            'name' => 'test-comp',
            'ns' => 'Some\\Namespace\\View\\Components\\TestComp',
            'class' => 'TestComp',
        ],
    ]);

    /** @var GetComponentsAction&MockInterface $getComponents */
    $getComponents = \Mockery::mock(GetComponentsAction::class);
    $getComponents->allows(['execute' => $mockComps]);
    app()->instance(GetComponentsAction::class, $getComponents);

    $action = app(RegisterBladeComponentsAction::class);
<<<<<<< HEAD
    Assert::assertCount(1, $mockComps);
=======
    Assert::assertInstanceOf(RegisterBladeComponentsAction::class, $action);
    Assert::assertSame(1, $mockComps->count());
>>>>>>> 77b1e2748 (first)

    $action->execute($path, $namespace, $prefix);
});

it('does nothing if no components found', function (): void {
    $path = 'empty/path';
    $namespace = 'Empty\\Namespace';

    /** @var DataCollection<int, ComponentFileData> $mockComps */
    $mockComps = ComponentFileData::collection([]);

    /** @var GetComponentsAction&MockInterface $getComponents */
    $getComponents = \Mockery::mock(GetComponentsAction::class);
    $getComponents->allows(['execute' => $mockComps]);
    app()->instance(GetComponentsAction::class, $getComponents);

    $action = app(RegisterBladeComponentsAction::class);
    Assert::assertInstanceOf(RegisterBladeComponentsAction::class, $action);
<<<<<<< HEAD
    Assert::assertCount(0, $mockComps);
=======
    Assert::assertSame(0, $mockComps->count());
>>>>>>> 77b1e2748 (first)

    $action->execute($path, $namespace);
});
