<?php

declare(strict_types=1);

namespace Modules\Xot\Tests\Unit\Actions\Cast;

use Illuminate\Database\Eloquent\Model;
use Mockery;
use Mockery\MockInterface;
use Modules\Activity\Models\Activity;
use Modules\Xot\Actions\Cast\SafeArrayByModelCastAction;
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

>>>>>>> laraxot/dev
describe('Safe Array By Model Cast Action', function (): void {
    test('converts model attributes to array correctly', function (): void {
        $model = new Activity;
        $model->setRawAttributes(['name' => 'Test']);

        $action = app(SafeArrayByModelCastAction::class);
        $result = $action->execute($model);

        Assert::assertIsArray($result);
        Assert::assertArrayHasKey('name', $result);
    });

    test('falls back to safe execute on error', function (): void {
        /** @var Model&MockInterface $model */
        $model = Mockery::mock(Model::class);
<<<<<<< HEAD
        $model->shouldReceive('attributesToArray')->andThrow(new \Exception('Mock error'));
=======
        $model->shouldReceive('attributesToArray')->andThrow(new \ValueError('Mock error'));
>>>>>>> laraxot/dev
        $model->shouldReceive('getAttributes')->andReturn(['name' => 'Fallback']);
        $model->shouldReceive('getAttribute')->andReturn('Fallback');

        $action = app(SafeArrayByModelCastAction::class);
        $result = $action->execute($model);

        Assert::assertIsArray($result);
        Assert::assertArrayHasKey('name', $result);
        Assert::assertSame('Fallback', $result['name']);
    });
});
