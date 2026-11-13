<?php

declare(strict_types=1);
use Illuminate\Database\Eloquent\Builder;
use Mockery\MockInterface;
use Modules\Xot\Tests\Fixtures\Models\SchemalessTestModel;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Spatie\SchemalessAttributes\SchemalessAttributes;

uses(TestCase::class);

it('handles extra attributes scope', function (): void {
    /** @var MockInterface&Builder<SchemalessTestModel> $builder */
    $builder = Mockery::mock(Builder::class);

    $model = new SchemalessTestModel;
    $model->extra_attributes = SchemalessAttributes::createForModel($model, 'extra_attributes');

    $result = $model->scopeWithExtraAttributes($builder);
    Assert::assertSame($builder, $result);
    Mockery::close();
});

it('handles where extra attribute scope', function (): void {
    /** @var MockInterface&Builder<SchemalessTestModel> $builder */
    $builder = Mockery::mock(Builder::class);
    $builder->allows(['where' => $builder]);

    $model = new SchemalessTestModel;

    $result = $model->scopeWhereExtraAttribute($builder, 'key', 'value');
    Assert::assertSame($builder, $result);
    Mockery::close();
});

it('gets and sets extra attributes', function (): void {
    $model = new SchemalessTestModel;
<<<<<<< HEAD
    $model->setExtraValue('foo', 'bar');

    Assert::assertSame('bar', $model->getExtraValue('foo'));
=======
    $model->setExtraAttribute('foo', 'bar');

    Assert::assertSame('bar', $model->getExtraAttribute('foo'));
>>>>>>> 77b1e2748 (first)
    Assert::assertTrue($model->hasExtraAttribute('foo'));
    Assert::assertFalse($model->hasExtraAttribute('baz'));
});

it('returns all extra attributes as array', function (): void {
    $model = new SchemalessTestModel;
<<<<<<< HEAD
    $model->setExtraValue('a', 1);
=======
    $model->setExtraAttribute('a', 1);
>>>>>>> 77b1e2748 (first)

    Assert::assertSame(['a' => 1], $model->getExtraAttributes());
});

it('removes extra attribute', function (): void {
    $model = new SchemalessTestModel;
<<<<<<< HEAD
    $model->setExtraValue('temp', 'val');
=======
    $model->setExtraAttribute('temp', 'val');
>>>>>>> 77b1e2748 (first)

    Assert::assertTrue($model->hasExtraAttribute('temp'));
    $model->removeExtraAttribute('temp');
    Assert::assertFalse($model->hasExtraAttribute('temp'));
});
