<?php

declare(strict_types=1);

namespace Modules\Tenant\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Tenant\Models\BaseModel;
use Modules\Tenant\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(\Modules\Tenant\Tests\TestCase::class);

function makeTenantTestBaseModel(): BaseModel
{
    return new class() extends BaseModel
    {
        protected $table = 'test_tenant_table';
    };
}

test('base model extends eloquent model', function (): void {
    Assert::assertInstanceOf(Model::class, makeTenantTestBaseModel());
});

test('base model has correct table name', function (): void {
    Assert::assertSame('test_tenant_table', makeTenantTestBaseModel()->getTable());
});

test('base model can be instantiated', function (): void {
    Assert::assertInstanceOf(BaseModel::class, makeTenantTestBaseModel());
});

test('base model has timestamps enabled', function (): void {
    Assert::assertTrue(makeTenantTestBaseModel()->usesTimestamps());
});
