<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Notify\Models\BaseModel;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
test('base model extends eloquent model', function () {
    $baseModel = new class extends BaseModel
=======
use PHPUnit\Framework\Assert;

test('base model extends eloquent model', function () {
    $baseModel = new class() extends BaseModel
>>>>>>> a988596b (first)
    {
        protected $table = 'test_notify_table';
    };

    Assert::assertInstanceOf(Model::class, $baseModel);
});

test('base model has correct table name', function () {
<<<<<<< HEAD
    $baseModel = new class extends BaseModel
=======
    $baseModel = new class() extends BaseModel
>>>>>>> a988596b (first)
    {
        protected $table = 'test_notify_table';
    };

    Assert::assertSame('test_notify_table', $baseModel->getTable());
});

test('base model can be instantiated', function () {
<<<<<<< HEAD
    $baseModel = new class extends BaseModel
=======
    $baseModel = new class() extends BaseModel
>>>>>>> a988596b (first)
    {
        protected $table = 'test_notify_table';
    };

    Assert::assertInstanceOf(BaseModel::class, $baseModel);
});

test('base model has proper inheritance chain', function () {
<<<<<<< HEAD
    $baseModel = new class extends BaseModel
=======
    $baseModel = new class() extends BaseModel
>>>>>>> a988596b (first)
    {
        protected $table = 'test_notify_table';
    };

    Assert::assertInstanceOf(BaseModel::class, $baseModel);
    Assert::assertInstanceOf(Model::class, $baseModel);
});

test('base model has timestamps enabled', function () {
<<<<<<< HEAD
    $baseModel = new class extends BaseModel
=======
    $baseModel = new class() extends BaseModel
>>>>>>> a988596b (first)
    {
        protected $table = 'test_notify_table';
    };

    Assert::assertTrue($baseModel->usesTimestamps());
});
