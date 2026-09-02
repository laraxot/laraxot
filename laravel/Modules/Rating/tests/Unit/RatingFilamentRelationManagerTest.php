<?php

declare(strict_types=1);

namespace Modules\Rating\Tests\Unit;

use Filament\Tables\Columns\Column;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Mockery\MockInterface;
use Modules\Rating\Filament\RelationManagers\RatingsRelationManager;
use Modules\Rating\Filament\Resources\HasRatingResource\RelationManagers\RatingsRelationManager as HasRatingRatingsRelationManager;
use Modules\Rating\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

afterEach(function (): void {
    \Mockery::close();
});

/**
 * @return list<string>
 */
function ratingRelationManagerColumnNames(RatingsRelationManager|HasRatingRatingsRelationManager $manager): array
{
    /** @var HasTable&MockInterface $livewire */
    $livewire = \Mockery::mock(HasTable::class);

    $table = $manager->table(Table::make($livewire));

    return array_values(array_map(
        static fn (Column $column): string => $column->getName(),
        $table->getColumns(),
    ));
}

test('RatingsRelationManager dichiara colonne pivot e azioni CRUD', function (): void {
    $manager = new RatingsRelationManager();

    Assert::assertSame('ratings', $manager::getRelationshipName());

    $names = ratingRelationManagerColumnNames($manager);

    Assert::assertSame(
        ['id', 'title', 'pivot.user.name', 'value', 'is_winner', 'reward', 'updated_at'],
        $names,
    );
});

test('HasRatingResource RatingsRelationManager allinea lo schema tabella', function (): void {
    $manager = new HasRatingRatingsRelationManager();

    Assert::assertSame('ratings', $manager::getRelationshipName());

    $names = ratingRelationManagerColumnNames($manager);

    Assert::assertContains('id', $names);
    Assert::assertContains('title', $names);
    Assert::assertContains('value', $names);
    Assert::assertContains('is_winner', $names);
});
