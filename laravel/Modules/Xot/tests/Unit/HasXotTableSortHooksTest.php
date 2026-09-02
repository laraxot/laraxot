<?php

declare(strict_types=1);

use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;
use Modules\Xot\Tests\Unit\Support\DummyTestModel;
use PHPUnit\Framework\Assert;

uses(PHPUnit\Framework\TestCase::class);

/**
 * @param object $instance
 */
function invokeProtectedSortHook(object $instance, string $method): mixed
{
    $reflection = new ReflectionMethod($instance, $method);

    return $reflection->invoke($instance);
}

test('getTableSortColumn default su XotBaseResourceTable', function (): void {
    $table = new class extends XotBaseResourceTable
    {
        public function getTableColumns(): array
        {
            return [];
        }

        public function getModelClass(): string
        {
            return DummyTestModel::class;
        }
    };

    Assert::assertSame('dummy_test_models.id', invokeProtectedSortHook($table, 'getTableSortColumn'));
    Assert::assertSame('desc', invokeProtectedSortHook($table, 'getTableSortDirection'));
});

test('getTableSortColumn override su XotBaseResourceTable', function (): void {
    $table = new class extends XotBaseResourceTable
    {
        public function getTableColumns(): array
        {
            return [];
        }

        public function getModelClass(): string
        {
            return DummyTestModel::class;
        }

        public function getTableSortColumn(): string
        {
            return 'custom.id';
        }

        public function getTableSortDirection(): string
        {
            return 'asc';
        }
    };

    Assert::assertSame('custom.id', invokeProtectedSortHook($table, 'getTableSortColumn'));
    Assert::assertSame('asc', invokeProtectedSortHook($table, 'getTableSortDirection'));
});
