<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\RoleResource\RelationManagers;

<<<<<<< HEAD
<<<<<<< HEAD
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
=======
=======
>>>>>>> 87273113 (.)
use Filament\Tables\Columns\Layout\Component;
use Filament\Tables\Filters\BaseFilter;
use Override;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Modules\UI\Enums\TableLayoutEnum;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
use Modules\Xot\Filament\Traits\HasXotTable;
use Modules\Xot\Filament\Traits\TransTrait;
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Columns\Layout\Component;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

/**
 * UsersRelationManager.
 *
 * Manages the relationship between users and roles, providing functionality
 * for viewing, filtering, and managing users associated with a specific role.
 */
final class UsersRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'users';

<<<<<<< HEAD
<<<<<<< HEAD
    protected static ?string $inverseRelationship = 'roles';
=======
    protected static null|string $inverseRelationship = 'roles';
>>>>>>> f548be94 (.)
=======
    protected static null|string $inverseRelationship = 'roles';
=======
    protected static ?string $inverseRelationship = 'roles';
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

    /**
     * Returns the form schema structure, defining the input fields for user data.
     *
     * @return array<\Filament\Schemas\Components\Component>
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
            // Additional fields can be added here as necessary
        ];
    }

    /**
     * Defines the columns displayed in the users list table.
     *
     * @return array<Tables\Columns\Column|Component>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')
                ->searchable()
                ->sortable()
                ->copyable(),
            'email' => TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->copyable(),
            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(),
            'updated_at' => TextColumn::make('updated_at')
=======
=======
>>>>>>> 87273113 (.)
    #[Override]
    public function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable()
                ->sortable()
                ->copyable(),
            TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->copyable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(),
            TextColumn::make('updated_at')
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    #[\Override]
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')
                ->searchable()
                ->sortable()
                ->copyable(),
            'email' => TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->copyable(),
            'created_at' => TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(),
            'updated_at' => TextColumn::make('updated_at')
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * Configures available filters for the table, enabling users to refine their view.
     *
     * @return array<BaseFilter>
     */
<<<<<<< HEAD
<<<<<<< HEAD
    #[\Override]
    public function getTableFilters(): array
    {
        return [
            Filter::make('active')->query(fn (Builder $query): Builder => $query->where('is_active', true))->toggle(),
=======
=======
>>>>>>> 87273113 (.)
    #[Override]
    public function getTableFilters(): array
    {
        return [
            Filter::make('active')->query(fn(Builder $query): Builder => $query->where('is_active', true))->toggle(),
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
    #[\Override]
    public function getTableFilters(): array
    {
        return [
            Filter::make('active')->query(fn (Builder $query): Builder => $query->where('is_active', true))->toggle(),
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            Filter::make('created_at')
                ->schema([
                    DatePicker::make('created_from'),
                    DatePicker::make('created_until'),
                ])
<<<<<<< HEAD
<<<<<<< HEAD
                ->query(function (Builder $query, array $data): Builder {
                    if (isset($data['created_from']) && is_string($data['created_from']) && '' !== $data['created_from']) {
                        $query->whereDate('created_at', '>=', $data['created_from']);
                    }

                    if (isset($data['created_until']) && is_string($data['created_until']) && '' !== $data['created_until']) {
                        $query->whereDate('created_at', '<=', $data['created_until']);
                    }

                    return $query;
                })
=======
=======
>>>>>>> 87273113 (.)
                ->query(fn(Builder $query, array $data): Builder => $query->when($data['created_from'], fn(
                    Builder $query,
                    $date,
                ) => $query->whereDate('created_at', '>=', $date))->when($data['created_until'], fn(
                    Builder $query,
                    $date,
                ) => $query->whereDate('created_at', '<=', $date)))
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
                ->query(function (Builder $query, array $data): Builder {
                    if (isset($data['created_from']) && is_string($data['created_from']) && '' !== $data['created_from']) {
                        $query->whereDate('created_at', '>=', $data['created_from']);
                    }

                    if (isset($data['created_until']) && is_string($data['created_until']) && '' !== $data['created_until']) {
                        $query->whereDate('created_at', '<=', $data['created_until']);
                    }

                    return $query;
                })
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
                ->columns(2),
        ];
    }
}
