<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\Platform\Filament\Concerns\GatesResourceNavigation;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class WorkOrderChangeOrderResource extends XotBaseResource
{
    use GatesResourceNavigation;

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

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'section' => Section::make()->columnSpanFull()->components([
                'work_order_id' => Select::make('work_order_id')
                    ->required()
                    ->options(fn (): array => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('title', 'id')->all())
                    ->searchable(),
                'grid' => Grid::make(3)->components([
                    'title' => TextInput::make('title')->required()->maxLength(255),
                    'estimated_amount' => TextInput::make('estimated_amount')->numeric()->prefix('EUR'),
                    'status' => Select::make('status')->default('proposed')->options(self::statuses()),
                ]),
                'description' => Textarea::make('description')->columnSpanFull(),
            ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('workOrder.title')->limit(40)->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('estimated_amount')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(self::statuses()),
            ])
            ->recordActions([ViewAction::make(), EditAction::make()])
            ->toolbarActions([DeleteBulkAction::make()]);
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
