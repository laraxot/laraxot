<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\CustomerResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class CustomerSitesRelationManager extends RelationManager
{
    protected static string $relationship = 'sites';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('customers.sites');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label(__('customers.fields.name'))
                ->maxLength(255),
            TextInput::make('address_line1')
                ->label(__('customers.fields.address_line1'))
                ->maxLength(255),
            Grid::make(3)->components([
                TextInput::make('city')->label(__('customers.fields.city'))->maxLength(255),
                TextInput::make('postal_code')->label(__('customers.fields.postal_code'))->maxLength(255),
                TextInput::make('province')->label(__('customers.fields.province'))->maxLength(255),
            ]),
            TextInput::make('email')->label(__('customers.fields.email'))->email()->maxLength(255),
            TextInput::make('phone')->label(__('customers.fields.phone'))->tel()->maxLength(255),
            Textarea::make('notes')->label(__('customers.fields.notes'))->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('customers.fields.name'))->searchable(),
                Tables\Columns\TextColumn::make('city')->label(__('customers.fields.city'))->toggleable(),
                Tables\Columns\TextColumn::make('email')->label(__('customers.fields.email'))->toggleable(),
                Tables\Columns\TextColumn::make('phone')->label(__('customers.fields.phone'))->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }
}
