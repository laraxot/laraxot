<?php

declare(strict_types=1);

namespace Modules\Media\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Media\Models\BaseModel;
use Modules\Media\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-media-db');

if (! function_exists(__NAMESPACE__.'\\makeMediaTestBaseModel')) {
    function makeMediaTestBaseModel(): BaseModel
    {
        return new class() extends BaseModel
        {
            protected $table = 'test_media_table';
        };
    }
}

test('base model extends eloquent model', function (): void {
    Assert::assertInstanceOf(Model::class, makeMediaTestBaseModel());
});

test('base model has correct table name', function (): void {
    expect(makeMediaTestBaseModel()->getTable())->toBe('test_media_table');
});

test('base model can be instantiated', function (): void {
    Assert::assertInstanceOf(BaseModel::class, makeMediaTestBaseModel());
});

test('base model has proper inheritance chain', function (): void {
    $model = makeMediaTestBaseModel();
    Assert::assertInstanceOf(BaseModel::class, $model);
    Assert::assertInstanceOf(Model::class, $model);
});

test('base model has timestamps enabled', function (): void {
    expect(makeMediaTestBaseModel()->usesTimestamps())->toBeTrue();
});
