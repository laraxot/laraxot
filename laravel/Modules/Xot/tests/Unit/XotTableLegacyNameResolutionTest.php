<?php

declare(strict_types=1);

<<<<<<< HEAD
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\ColumnGroup;
use Filament\Tables\Columns\Layout\Component as LayoutComponent;
use Modules\Xot\Filament\Traits\HasXotTable;
=======
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
use Modules\Xot\Tests\TestCase;
use Modules\Xot\Tests\Unit\Fixtures\LegacyTableNameFixture;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('un override di getTableFilters viene onorato', function (): void {
    $fixture = new LegacyTableNameFixture();

    Assert::assertSame(['legacy_filter'], array_keys($fixture->getTableFilters()));
});

test('senza override si ricade sul default vuoto', function (): void {
<<<<<<< HEAD
    $fixture = new class()
    {
        use HasXotTable;

        public string $tableSearch = '';

        /** @return array<string, Column> */
=======
    $fixture = new class
    {
        use Modules\Xot\Filament\Traits\HasXotTable;

        public string $tableSearch = '';

        /** @return array<string, mixed> */
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
        public function getTableColumns(): array
        {
            return [];
        }
    };

    Assert::assertSame([], $fixture->getTableFilters());
});
<<<<<<< HEAD
=======

>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
