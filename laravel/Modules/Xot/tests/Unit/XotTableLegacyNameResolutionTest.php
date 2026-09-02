<?php

declare(strict_types=1);

use Modules\Xot\Tests\TestCase;
use Modules\Xot\Tests\Unit\Fixtures\LegacyTableNameFixture;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('un override di getTableFilters viene onorato', function (): void {
    $fixture = new LegacyTableNameFixture();

    Assert::assertSame(['legacy_filter'], array_keys($fixture->getTableFilters()));
});

test('senza override si ricade sul default vuoto', function (): void {
    $fixture = new class
    {
        use Modules\Xot\Filament\Traits\HasXotTable;

        public string $tableSearch = '';

        /** @return array<string, mixed> */
        public function getTableColumns(): array
        {
            return [];
        }
    };

    Assert::assertSame([], $fixture->getTableFilters());
});

