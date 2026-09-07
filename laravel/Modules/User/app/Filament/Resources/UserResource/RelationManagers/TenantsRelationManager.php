<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\UserResource\RelationManagers;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Modules\User\Filament\Resources\TenantResource\Pages\ListTenants;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Schemas\Components\Component;
use Override;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\Column;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Modules\User\Filament\Resources\TenantResource\Pages\ListTenants;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
use Modules\Xot\Filament\Traits\HasXotTable;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables\Columns\Column;
use Modules\User\Filament\Resources\TenantResource\Pages\ListTenants;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * Manages the relationship between users and tenants.
 *
 * This class provides the form schema and table configuration for the "tenants" relationship
 * with strong typing and enhanced structure for stability and professionalism.
 */
class TenantsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'tenants';

<<<<<<< HEAD
<<<<<<< HEAD
    protected static ?string $recordTitleAttribute = 'name';
=======
    protected static null|string $recordTitleAttribute = 'name';
>>>>>>> f548be94 (.)
=======
    protected static null|string $recordTitleAttribute = 'name';
=======
    protected static ?string $recordTitleAttribute = 'name';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Set up the form schema for tenant relations.
     *
     * @return array<Component>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
=======
    #[Override]
>>>>>>> f548be94 (.)
=======
    #[Override]
=======
    #[\Override]
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function getFormSchema(): array
    {
        return [
            TextInput::make('name')->required()->maxLength(255),
        ];
    }

    /**
     * Define table columns for displaying tenant information.
     *
     * @return array<string, Column>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
    public function getTableColumns(): array
    {
        $listTenants = app(ListTenants::class);

        if (! method_exists($listTenants, 'getTableColumns')) {
            return [];
        }

        $columns = $listTenants->getTableColumns();

        /** @var array<string, Column> $columnMap */
        $columnMap = [];
        foreach ($columns as $column) {
            if (! $column instanceof Column) {
                continue;
            }

            $columnMap[(string) $column->getName()] = $column;
        }

        return $columnMap;
=======
=======
>>>>>>> 87273113 (.)
    #[Override]
    public function getTableColumns(): array
    {
        $columns = app(ListTenants::class)->getTableColumns();

        // Ensure we only return Column instances, filter out any Layout\Component instances
        return array_filter($columns, fn($column): bool => $column instanceof Column);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    #[\Override]
    public function getTableColumns(): array
    {
        $listTenants = app(ListTenants::class);

        if (! method_exists($listTenants, 'getTableColumns')) {
            return [];
        }

        $columns = $listTenants->getTableColumns();

        /** @var array<string, Column> $columnMap */
        $columnMap = [];
        foreach ($columns as $column) {
            if (! $column instanceof Column) {
                continue;
            }

            $columnMap[(string) $column->getName()] = $column;
        }

        return $columnMap;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    }
}
