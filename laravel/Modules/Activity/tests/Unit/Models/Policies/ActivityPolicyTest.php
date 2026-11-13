<?php

declare(strict_types=1);

use Modules\Activity\Models\Policies\ActivityBasePolicy;
use Modules\Activity\Models\Policies\ActivityPolicy;
use Modules\Activity\Models\Policies\SnapshotPolicy;
use Modules\Activity\Models\Policies\StoredEventPolicy;
use Modules\Activity\Tests\TestCase;
use Modules\User\Models\Policies\UserBasePolicy;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
uses(\Modules\Activity\Tests\TestCase::class);

test('ActivityPolicy can be instantiated', function () {
<<<<<<< .merge_file_5PNKnv
    $policy = new ActivityPolicy();
=======
uses(TestCase::class);

test('ActivityPolicy can be instantiated', function () {
    $policy = new ActivityPolicy;
>>>>>>> 13cf1831 (.)

    Assert::assertInstanceOf(ActivityPolicy::class, $policy);
=======
    $reflection = new ReflectionClass(ActivityPolicy::class);
    Assert::assertTrue($reflection->isInstantiable());
    Assert::assertTrue($reflection->isSubclassOf(UserBasePolicy::class));
>>>>>>> .merge_file_Qhk9Ui
});

test('ActivityBasePolicy is an abstract class', function () {
    $reflection = new ReflectionClass(ActivityBasePolicy::class);

    Assert::assertTrue($reflection->isAbstract());
});

test('SnapshotPolicy can be instantiated', function () {
<<<<<<< HEAD
<<<<<<< .merge_file_5PNKnv
    $policy = new SnapshotPolicy();
=======
    $policy = new SnapshotPolicy;
>>>>>>> 13cf1831 (.)

    Assert::assertInstanceOf(SnapshotPolicy::class, $policy);
});

test('StoredEventPolicy can be instantiated', function () {
<<<<<<< HEAD
    $policy = new StoredEventPolicy();
=======
    $policy = new StoredEventPolicy;
>>>>>>> 13cf1831 (.)

    Assert::assertInstanceOf(StoredEventPolicy::class, $policy);
=======
    $reflection = new ReflectionClass(SnapshotPolicy::class);
    Assert::assertTrue($reflection->isInstantiable());
    Assert::assertTrue($reflection->isSubclassOf(UserBasePolicy::class));
});

test('StoredEventPolicy can be instantiated', function () {
    $reflection = new ReflectionClass(StoredEventPolicy::class);
    Assert::assertTrue($reflection->isInstantiable());
    Assert::assertTrue($reflection->isSubclassOf(UserBasePolicy::class));
>>>>>>> .merge_file_Qhk9Ui
});

test('ActivityPolicy method signatures', function () {
<<<<<<< HEAD
    $policy = new ActivityPolicy();
=======
    $policy = new ActivityPolicy;
>>>>>>> 13cf1831 (.)
    $reflection = new ReflectionClass($policy);
    $expectedMethods = ['view', 'create', 'update', 'delete', 'restore', 'forceDelete'];

    foreach ($expectedMethods as $methodName) {
        Assert::assertTrue($reflection->hasMethod($methodName), "Missing method: {$methodName}");
        $method = $reflection->getMethod($methodName);
        Assert::assertCount(1, $method->getParameters());
    }
});
