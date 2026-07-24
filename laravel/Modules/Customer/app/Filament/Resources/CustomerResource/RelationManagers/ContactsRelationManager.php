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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Customer\Models\ContactRole;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class ContactsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'contacts';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('customers.contacts');
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('full_name')
                ->label(__('customers.fields.full_name'))
                ->required()
                ->maxLength(255),

            Select::make('contact_role_id')
                ->label(__('customers.fields.role'))
                ->options(fn () => ContactRole::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->preload(),

            TextInput::make('email')
                ->label(__('customers.fields.email'))
                ->email()
                ->maxLength(255),

            TextInput::make('phone')
                ->label(__('customers.fields.phone'))
                ->tel()
                ->maxLength(255),

            Textarea::make('notes')
                ->label(__('customers.fields.notes'))
                ->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label(__('customers.fields.full_name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role.name')
                    ->label(__('customers.fields.role'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('customers.fields.email'))
                    ->toggleable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('customers.fields.phone'))
                    ->toggleable(),
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
