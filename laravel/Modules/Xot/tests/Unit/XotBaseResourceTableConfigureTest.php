<?php

declare(strict_types=1);
use Filament\Tables\Table;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;
<<<<<<< HEAD
use Modules\Xot\Tests\Fixtures\Filament\Resources\ExtraResource\Tables\XotBaseResourceTableConfigureFixture;
use Modules\Xot\Tests\TestCase;
=======
use Modules\Xot\Tests\TestCase;
use Modules\Xot\Tests\Unit\Fixtures\XotBaseResourceTableConfigureFixture;
>>>>>>> 77b1e2748 (first)
use Modules\Xot\Tests\Unit\Fixtures\XotTableConfigureLivewireHarness;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('XotBaseResourceTable configure applica colonne e filtri dalla classe table', function (): void {
    $livewire = new XotTableConfigureLivewireHarness;
    $table = Table::make($livewire);

    $configured = XotBaseResourceTableConfigureFixture::configure($table);

<<<<<<< HEAD
    Assert::assertSame($table, $configured);
=======
    Assert::assertInstanceOf(Table::class, $configured);
>>>>>>> 77b1e2748 (first)
});

test('XotBaseResourceTable configure su classe astratta solleva LogicException', function (): void {
    $livewire = new XotTableConfigureLivewireHarness;
    $table = Table::make($livewire);

    expect(fn (): Table => XotBaseResourceTable::configure($table))
        ->toThrow(LogicException::class);
});
