<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class ChangeOrdersRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'changeOrders';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('compliance.change_orders');
    }

    public function getFormSchema(): array
    {
        return [
            Grid::make(3)->components([
                TextInput::make('title')->label(__('compliance.fields.title'))->required()->maxLength(255),
                TextInput::make('estimated_amount')->label(__('compliance.fields.estimated_amount'))->numeric()->prefix('EUR'),
                Select::make('status')->label(__('compliance.fields.status'))->default('proposed')->options([
                    'proposed' => 'Proposta',
                    'pending_approval' => 'Da approvare',
                    'approved' => 'Approvata',
                    'executed' => 'Eseguita',
                    'billed' => 'Fatturata',
                    'rejected' => 'Respinta',
                ]),
            ]),
            Textarea::make('description')->label(__('compliance.fields.description'))->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label(__('compliance.fields.title'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('estimated_amount')->label(__('compliance.fields.estimated_amount'))->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('compliance.fields.status'))->badge()->sortable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
