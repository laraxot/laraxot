<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class PublicProcurementContractsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'publicProcurementContracts';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('public_procurement.contracts');
    }

    public function getFormSchema(): array
    {
        return [
            TextInput::make('code')->maxLength(255),
            Select::make('status')->options([
                'draft' => __('public_procurement.statuses.draft'),
                'tender_participation' => __('public_procurement.statuses.tender_participation'),
                'active' => __('public_procurement.statuses.active'),
                'completed' => __('public_procurement.statuses.completed'),
            ])->default('draft'),
            TextInput::make('title')->required()->maxLength(255),
            TextInput::make('contracting_authority')->required()->maxLength(255),
            TextInput::make('cig')->maxLength(255),
            TextInput::make('cup')->maxLength(255),
            TextInput::make('contract_amount')->numeric()->default(0),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('code')->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable()->limit(60),
                Tables\Columns\TextColumn::make('cig')->searchable(),
                Tables\Columns\TextColumn::make('contract_amount')->money('EUR')->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make()]);
    }
}
