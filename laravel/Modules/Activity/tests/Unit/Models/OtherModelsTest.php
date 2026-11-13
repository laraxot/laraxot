<?php

declare(strict_types=1);
use Modules\Activity\Models\BaseModel;
use Modules\Activity\Models\Snapshot;
use Modules\Activity\Models\StoredEvent;
use Modules\Activity\Tests\TestCase;
use PHPUnit\Framework\Assert;

<<<<<<< HEAD
uses(\Modules\Activity\Tests\TestCase::class);

test('Snapshot model can be instantiated', function () {
<<<<<<< .merge_file_qJZkId
    $snapshot = new Snapshot();
=======
uses(TestCase::class);

test('Snapshot model can be instantiated', function () {
    $snapshot = new Snapshot;
>>>>>>> 13cf1831 (.)

    Assert::assertInstanceOf(Snapshot::class, $snapshot);
});

test('StoredEvent model can be instantiated', function () {
<<<<<<< HEAD
    $storedEvent = new StoredEvent();
=======
    $storedEvent = new StoredEvent;
>>>>>>> 13cf1831 (.)

    Assert::assertInstanceOf(StoredEvent::class, $storedEvent);
=======
    Assert::assertTrue((new ReflectionClass(Snapshot::class))->isInstantiable());
});

test('StoredEvent model can be instantiated', function () {
    Assert::assertTrue((new ReflectionClass(StoredEvent::class))->isInstantiable());
>>>>>>> .merge_file_vm1ZBF
});

test('BaseModel model can be instantiated', function () {
<<<<<<< HEAD
    $baseModel = new class() extends BaseModel
=======
    $baseModel = new class extends BaseModel
>>>>>>> 13cf1831 (.)
    {
        protected $table = 'activity_base_models';
    };

    Assert::assertSame('activity_base_models', $baseModel->getTable());
});

test('Snapshot model has correct connection', function () {
<<<<<<< HEAD
    $snapshot = new Snapshot();
=======
    $snapshot = new Snapshot;
>>>>>>> 13cf1831 (.)

    Assert::assertIsString($snapshot->getConnectionName());
});

test('StoredEvent model has correct connection', function () {
<<<<<<< HEAD
    $storedEvent = new StoredEvent();
=======
    $storedEvent = new StoredEvent;
>>>>>>> 13cf1831 (.)

    Assert::assertIsString($storedEvent->getConnectionName());
});

test('BaseModel model has correct connection', function () {
<<<<<<< HEAD
    $baseModel = new class() extends BaseModel
=======
    $baseModel = new class extends BaseModel
>>>>>>> 13cf1831 (.)
    {
        protected $table = 'activity_base_models';
    };

    Assert::assertIsString($baseModel->getConnectionName());
});
