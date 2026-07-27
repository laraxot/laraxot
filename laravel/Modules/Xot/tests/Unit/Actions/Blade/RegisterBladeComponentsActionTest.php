<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\Actions\Blade;

use Illuminate\Support\Facades\Blade;
use Mockery;
use Modules\Xot\Actions\Blade\RegisterBladeComponentsAction;
use Modules\Xot\Actions\File\GetComponentsAction;
use Modules\Xot\Datas\ComponentFileData;
use Modules\Xot\Tests\TestCase;
use Spatie\LaravelData\DataCollection;

uses(TestCase::class);

afterEach(function (): void {
    Mockery::close();
});

it('registers blade components correctly', function (): void {
    $path = 'some/path';
    $namespace = 'Some\\Namespace';
    $prefix = 'prefix';

    $comp1 = ComponentFileData::from([
        'name' => 'test-comp',
        'ns' => 'Some\\Namespace\\View\\Components\\TestComp',
        'class' => 'TestComp',
    ]);

    $mockComps = new DataCollection(ComponentFileData::class, [$comp1]);

    $mock = Mockery::mock(GetComponentsAction::class);
    $expectation = $mock->shouldReceive('execute');
    assert($expectation instanceof Mockery\Expectation);
    $expectation
        ->once()
        ->with($path, $namespace.'\\View\\Components', $prefix)
        ->andReturn($mockComps);

    app()->instance(GetComponentsAction::class, $mock);

    Blade::shouldReceive('component')
        ->once()
        ->with('test-comp', 'Some\\Namespace\\View\\Components\\TestComp');

    $action = app(RegisterBladeComponentsAction::class);
    $action->execute($path, $namespace, $prefix);
});

it('does nothing if no components found', function (): void {
    $path = 'empty/path';
    $namespace = 'Empty\\Namespace';

    $mockComps = new DataCollection(ComponentFileData::class, []);

    $mock = Mockery::mock(GetComponentsAction::class);
    $expectation = $mock->shouldReceive('execute');
    assert($expectation instanceof Mockery\Expectation);
    $expectation->once()->andReturn($mockComps);

    app()->instance(GetComponentsAction::class, $mock);

    Blade::shouldReceive('component')->never();

    $action = app(RegisterBladeComponentsAction::class);
    $action->execute($path, $namespace);
});
