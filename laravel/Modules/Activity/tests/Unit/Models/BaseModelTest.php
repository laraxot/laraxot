<?php

declare(strict_types=1);
use Modules\Activity\Models\BaseModel;
use Modules\Activity\Tests\TestCase;
use Modules\Xot\Models\XotBaseModel;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
uses(\Modules\Activity\Tests\TestCase::class);

test('BaseModel has correct connection', function () {
    $model = new class() extends BaseModel
=======
uses(TestCase::class);

test('BaseModel has correct connection', function () {
    $model = new class extends BaseModel
>>>>>>> 13cf1831 (.)
    {
        protected $table = 'test_models';

        protected $fillable = ['name'];
    };
    $reflection = new ReflectionClass($model);
    $property = $reflection->getProperty('connection');
    $property->setAccessible(true);

    Assert::assertSame('activity', $property->getValue($model));
});

test('BaseModel extends XotBaseModel', function () {
<<<<<<< HEAD
<<<<<<< .merge_file_DR8IeS
    $model = new class() extends BaseModel
=======
    $model = new class extends BaseModel
>>>>>>> 13cf1831 (.)
    {
        protected $table = 'test_models';

        protected $fillable = ['name'];
    };

    Assert::assertInstanceOf(XotBaseModel::class, $model);
=======
    Assert::assertTrue((new ReflectionClass(BaseModel::class))->isSubclassOf(XotBaseModel::class));
>>>>>>> .merge_file_1SaS26
});
