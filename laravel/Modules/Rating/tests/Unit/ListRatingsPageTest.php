<?php

declare(strict_types=1);

use Filament\Tables\Table;
use Modules\Rating\Filament\Resources\RatingResource;
use Modules\Rating\Filament\Resources\RatingResource\Pages\ListRatings;
use Modules\Rating\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('ListRatings uses correct resource', function (): void {
    $page = new ListRatings();
    Assert::assertSame(RatingResource::class, $page::getResource());
});

// Nota: getTableColumns()/getTableFilters()/getTableHeaderActions()/getTableActions()/
// getTableBulkActions() sono @deprecated in Filament v4 ("Override the `table()` method
// to configure the table"). Stesso pattern già validato in RatingFilamentExtendedTest.php
// (story 2.3.phpstan-rating-tail-contracts): si costruisce la Table via table()/Table::make()
// e si ispezionano i metodi pubblici non deprecati.
test('defines expected table columns without labels', function (): void {
    $page = new ListRatings();
    $table = $page->table(Table::make($page));

    Assert::assertSame(['id', 'title', 'rule', 'is_disabled', 'is_readonly'], array_keys($table->getColumns()));
});

test('defines default empty filters and header actions', function (): void {
    $page = new ListRatings();
    $table = $page->table(Table::make($page));

    Assert::assertSame([], $table->getFilters());
    Assert::assertNotEmpty($table->getHeaderActions());
});

test('defines view edit delete actions and bulk delete', function (): void {
    $page = new ListRatings();
    $table = $page->table(Table::make($page));

    Assert::assertTrue($table->hasAction('view'));
    Assert::assertTrue($table->hasAction('edit'));
    Assert::assertTrue($table->hasAction('delete'));
    Assert::assertTrue($table->hasBulkAction('delete'));
});
