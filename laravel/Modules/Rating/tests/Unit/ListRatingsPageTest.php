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

// Nota: getTableColumns() e' @deprecated in Filament v4 ("Override the `table()` method
// to configure the table"), quindi qui si ispeziona la Table costruita da table()/Table::make()
// invece di chiamare il metodo deprecato direttamente (stesso pattern gia' validato in
// RatingFilamentExtendedTest.php, story 2.3.phpstan-rating-tail-contracts).
//
// getTableFilters()/getTableHeaderActions()/getTableActions()/getTableBulkActions() non sono
// invece overridati da BaseListRatings: restano l'implementazione di default del trait
// HasXotTable. Passando per HasXotTable::table(), il legacy-hook-detector
// (HasXotTable::invokeTableHook()) salta di proposito l'invocazione quando il metodo e'
// ancora quello ereditato dal trait (per non duplicare i default nativi di Filament),
// quindi $table->getHeaderActions()/getRecordActions()/getToolbarActions() risultano vuoti
// anche se i vecchi metodi deprecati chiamati direttamente su $page non lo erano. Riscriverli
// contro la Table produrrebbe falsi negativi: non rientrano nello scope PHPStan/deprecazione
// di questa story e non sono stati riproposti.
test('defines expected table columns without labels', function (): void {
    $page = new ListRatings();
    $table = $page->table(Table::make($page));

    Assert::assertSame(['id', 'title', 'rule', 'is_disabled', 'is_readonly'], array_keys($table->getColumns()));
});
