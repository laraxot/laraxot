<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Unit;

use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Modules\Rating\Filament\Actions\Table\BetTableAction;
use Modules\Rating\Filament\Resources\RatingMorphResource\Pages\EditRatingMorph;
use Modules\Rating\Filament\Resources\RatingMorphResource\Pages\ListRatingMorphs;
use Modules\Rating\Filament\Resources\RatingResource\Pages\EditRating;
use Modules\Rating\Tests\Fixtures\BaseEditRatingStub;
use Modules\Rating\Tests\Fixtures\BaseRatingsTableStub;
use Modules\Rating\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

afterEach(function (): void {
    \Mockery::close();
});

test('BetTableAction espone il nome default bet_action', function (): void {
    Assert::assertSame('bet_action', BetTableAction::getDefaultName());
});

test('BetTableAction configura label e schema modale', function (): void {
    $action = BetTableAction::make();

    Assert::assertSame('bet_action', $action->getName());
    Assert::assertSame('', $action->getLabel());
});

test('BaseRatingsTable espone colonne filtri e azioni attese', function (): void {
    $tabella = new BaseRatingsTableStub();

    Assert::assertSame(
        ['id', 'title', 'slug', 'rule', 'is_disabled', 'is_readonly', 'order_column', 'created_at', 'updated_at'],
        array_keys($tabella->getTableColumns()),
    );
    Assert::assertContainsOnlyInstancesOf(Column::class, $tabella->getTableColumns());
    Assert::assertSame([], $tabella->getTableFilters());

    $actionsMethod = new \ReflectionMethod($tabella, 'getTableActions');
    $actions = $actionsMethod->invoke($tabella);
    Assert::assertIsArray($actions);
    Assert::assertArrayHasKey('edit', $actions);
    Assert::assertArrayHasKey('bulk', $tabella->getTableBulkActions());
});

test('BaseEditRating espone DeleteAction in header', function (): void {
    $method = new \ReflectionMethod(BaseEditRatingStub::class, 'getActions');
    $method->setAccessible(true);

    $actions = $method->invoke(new BaseEditRatingStub());

    Assert::assertIsArray($actions);
    Assert::assertCount(1, $actions);
});

test('EditRating espone DeleteAction in header', function (): void {
    $method = new \ReflectionMethod(EditRating::class, 'getActions');
    $method->setAccessible(true);

    $actions = $method->invoke(new EditRating());

    Assert::assertIsArray($actions);
    Assert::assertCount(1, $actions);
});

test('ListRatingMorphs dichiara colonne e create action', function (): void {
    $page = new ListRatingMorphs();
    $table = $page->table(Table::make($page));

    Assert::assertSame(
        ['id', 'rating', 'ratingable_type', 'ratingable_id', 'created_at', 'updated_at'],
        array_keys($table->getColumns()),
    );

    $actionsMethod = new \ReflectionMethod($page, 'getActions');
    $actionsMethod->setAccessible(true);

    $actions = $actionsMethod->invoke($page);
    Assert::assertIsArray($actions);
    Assert::assertCount(1, $actions);
});

test('EditRatingMorph eredita resource RatingMorph', function (): void {
    $method = new \ReflectionMethod(EditRatingMorph::class, 'getActions');
    $method->setAccessible(true);

    Assert::assertIsArray($method->invoke(new EditRatingMorph()));
});
