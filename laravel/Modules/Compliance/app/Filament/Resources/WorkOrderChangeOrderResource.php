<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\WorkOrder\Models\WorkOrder;

final class WorkOrderChangeOrderResource extends Resource
{
    protected static ?string $model = WorkOrderChangeOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-plus-circle';

    public static function getNavigationGroup(): string
    {
        return __('compliance.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('compliance.change_orders');
    }

    public static function getModelLabel(): string
    {
        return __('compliance.change_order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('compliance.change_orders');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columnSpanFull()->components([
                Select::make('work_order_id')
                    ->label('Commessa')
                    ->required()
                    ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('title', 'id')->all())
                    ->searchable(),
                Grid::make(3)->components([
                    TextInput::make('title')->label('Titolo')->required()->maxLength(255),
                    TextInput::make('estimated_amount')->label('Stima')->numeric()->prefix('EUR'),
                    Select::make('status')->label('Stato')->default('proposed')->options(self::statuses()),
                ]),
                Textarea::make('description')->label('Descrizione')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('workOrder.title')->label('Commessa')->limit(40)->searchable(),
                Tables\Columns\TextColumn::make('title')->label('Titolo')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('estimated_amount')->label('Stima')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('status')->label('Stato')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Stato')->options(self::statuses()),
            ])
            ->actions([ViewAction::make(), EditAction::make()])
            ->bulkActions([DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkOrderChangeOrders::route('/'),
            'create' => Pages\CreateWorkOrderChangeOrder::route('/create'),
            'view' => Pages\ViewWorkOrderChangeOrder::route('/{record}'),
            'edit' => Pages\EditWorkOrderChangeOrder::route('/{record}/edit'),
        ];
    }

    /** @return array<string, string> */
    private static function statuses(): array
    {
        return [
            'proposed' => 'Proposta',
            'pending_approval' => 'Da approvare',
            'approved' => 'Approvata',
            'executed' => 'Eseguita',
            'billed' => 'Fatturata',
            'rejected' => 'Respinta',
        ];
    }
}
