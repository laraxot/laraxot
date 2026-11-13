<?php

declare(strict_types=1);
use Filament\Tables\Table;
use Mockery\MockInterface;
use Modules\Xot\Tests\TestCase;
use Modules\Xot\Tests\Unit\Support\DummyTestModel;
use Modules\Xot\Tests\Unit\Support\HasTableWithoutOptionalMethodsTestClass;
use Modules\Xot\Tests\Unit\Support\HasTableWithXotTestClass;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

/**
<<<<<<< HEAD
 * @param  MockInterface&Table  $tableMock
=======
<<<<<<< .merge_file_1KO786
 * @param  MockInterface&Table  $tableMock
=======
 * <<<<<<< HEAD.
 *
 * @param MockInterface&Table $tableMock
 *                                       =======
 *                                       <<<<<<< HEAD
 * @param MockInterface&Table $tableMock
 *                                       =======
 *                                       <<<<<<< HEAD
 *                                       <<<<<<< HEAD
 * @param MockInterface&Table $tableMock
 *                                       =======
 * @param MockInterface&Table $tableMock
 *
 * >>>>>>> laraxot/dev
 *
 * =======
 * @param MockInterface&Table $tableMock
 *                                       >>>>>>> laraxot/dev
 *                                       >>>>>>> laraxot/dev
 *
 * >>>>>>> laraxot/dev
 *
>>>>>>> .merge_file_S7RwYV
>>>>>>> 77b1e2748 (first)
 * @return MockInterface&Table
 */
function stubTableChain(MockInterface $tableMock): MockInterface
{
    $chainMethods = [
        'recordTitleAttribute',
        'heading',
        'columns',
        'contentGrid',
        'filters',
        'filtersLayout',
        'filtersFormColumns',
        'deferFilters',
        'persistFiltersInSession',
        'headerActions',
        'actions',
        'bulkActions',
        'actionsPosition',
        'recordActions',
        'toolbarActions',
        'recordActionsPosition',
        'emptyStateActions',
        'striped',
<<<<<<< HEAD
        'stackedOnMobile',
=======
>>>>>>> 77b1e2748 (first)
        'paginated',
        'defaultSort',
        'poll',
    ];

    $allows = [];
    foreach ($chainMethods as $method) {
        $allows[$method] = $tableMock;
    }

    $tableMock->allows($allows);

    return $tableMock;
}

afterEach(function (): void {
    Mockery::close();
});

it('tests table method with all methods implemented', function (): void {
    Mockery::mock('overload:Modules\\Xot\\Actions\\Model\\TableExistsByModelClassActions')
        ->allows(['execute' => true]);

    /** @var HasTableWithXotTestClass&MockInterface $mock */
    $mock = Mockery::mock(HasTableWithXotTestClass::class)
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();
    $mock->allows([
        'getTableHeaderActions' => [],
        'getTableActions' => [],
        'getTableBulkActions' => [],
        'getModelClass' => DummyTestModel::class,
        'getTableRecordTitleAttribute' => 'name',
        'getTableHeading' => 'Test Table',
        'getTableFilters' => [],
        'getTableFiltersFormColumns' => 1,
        'getTableEmptyStateActions' => [],
        'getDefaultTableSortColumn' => null,
        'getDefaultTableSortDirection' => null,
        'getTablePollInterval' => null,
    ]);

    /** @var MockInterface&Table $tableMock */
    $tableMock = Mockery::mock(Table::class);
    stubTableChain($tableMock);

    $result = $mock->table($tableMock);

    Assert::assertSame($tableMock, $result);
});

it('tests table method with no optional methods implemented', function (): void {
    Mockery::mock('overload:Modules\\Xot\\Actions\\Model\\TableExistsByModelClassActions')
        ->allows(['execute' => true]);

    /** @var HasTableWithoutOptionalMethodsTestClass&MockInterface $mock */
    $mock = Mockery::mock(HasTableWithoutOptionalMethodsTestClass::class)
        ->makePartial()
        ->shouldAllowMockingProtectedMethods();
    $mock->allows([
        'getModelClass' => DummyTestModel::class,
        'getTableRecordTitleAttribute' => 'name',
        'getTableHeading' => 'Test Table',
        'getTableFilters' => [],
        'getTableHeaderActions' => [],
        'getTableActions' => [],
        'getTableBulkActions' => [],
        'getTableFiltersFormColumns' => 1,
        'getTableEmptyStateActions' => [],
        'getDefaultTableSortColumn' => null,
        'getDefaultTableSortDirection' => null,
        'getTablePollInterval' => null,
    ]);

    /** @var MockInterface&Table $tableMock */
    $tableMock = Mockery::mock(Table::class);
    stubTableChain($tableMock);

    $result = $mock->table($tableMock);

    Assert::assertSame($tableMock, $result);
});
