<?php

declare(strict_types=1);

use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Enums\RecordActionsPosition;
use Modules\Xot\Filament\Traits\HasXotTable;
use PHPUnit\Framework\Assert;

/**
 * @param object $instance
 */
function invokeProtectedTableHook(object $instance, string $method): mixed
{
    $reflection = new ReflectionMethod($instance, $method);

    return $reflection->invoke($instance);
}

test('getTableFiltersLayout default e override', function (): void {
    $default = new class
    {
        use HasXotTable;

        public string $tableSearch = '';

        /** @return array<string, \Filament\Tables\Columns\Column> */
        public function getTableColumns(): array
        {
            return [];
        }
    };

    Assert::assertSame(FiltersLayout::AboveContent, invokeProtectedTableHook($default, 'getTableFiltersLayout'));

    $custom = new class
    {
        use HasXotTable;

        public string $tableSearch = '';

        /** @return array<string, \Filament\Tables\Columns\Column> */
        public function getTableColumns(): array
        {
            return [];
        }

        protected function getTableFiltersLayout(): FiltersLayout
        {
            return FiltersLayout::Dropdown;
        }
    };

    Assert::assertSame(FiltersLayout::Dropdown, invokeProtectedTableHook($custom, 'getTableFiltersLayout'));
});

test('getTableRecordActionsPosition default e override', function (): void {
    $default = new class
    {
        use HasXotTable;

        public string $tableSearch = '';

        /** @return array<string, \Filament\Tables\Columns\Column> */
        public function getTableColumns(): array
        {
            return [];
        }
    };

    Assert::assertSame(RecordActionsPosition::BeforeColumns, invokeProtectedTableHook($default, 'getTableRecordActionsPosition'));

    $custom = new class
    {
        use HasXotTable;

        public string $tableSearch = '';

        /** @return array<string, \Filament\Tables\Columns\Column> */
        public function getTableColumns(): array
        {
            return [];
        }

        protected function getTableRecordActionsPosition(): RecordActionsPosition
        {
            return RecordActionsPosition::AfterColumns;
        }
    };

    Assert::assertSame(RecordActionsPosition::AfterColumns, invokeProtectedTableHook($custom, 'getTableRecordActionsPosition'));
});
