<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources;

use App\Support\AccountFeatures;
use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Modules\Catalog\Models\Product;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Production\Filament\Resources\ProductionOrderResource\Pages\CreateProductionOrder;
use Modules\Production\Filament\Resources\ProductionOrderResource\Pages\EditProductionOrder;
use Modules\Production\Filament\Resources\ProductionOrderResource\Pages\ListProductionOrders;
use Modules\Production\Filament\Resources\ProductionOrderResource\Pages\ViewProductionOrder;
use Modules\Production\Filament\Resources\ProductionOrderResource\RelationManagers\ProductionPhasesRelationManager;
use Modules\Production\Models\ProductionOrder;
use Modules\WorkOrder\Models\WorkOrder;

final class ProductionOrderResource extends GatedXotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = ProductionOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    private const ACCOUNT_FEATURE_ADMIN = 'admin.production';

    private const ACCOUNT_FEATURE_OPERATOR = null;

    private const ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;

    protected static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: self::ACCOUNT_FEATURE_ADMIN,
            operatorFeature: self::ACCOUNT_FEATURE_OPERATOR,
            operatorDefault: self::ACCOUNT_FEATURE_OPERATOR_DEFAULT,
            fallback: false,
        );
    }

    public static function getNavigationGroup(): string
    {
        return __('production.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('production.production_order');
    }

    public static function getPluralModelLabel(): string
    {
        return __('production.production_orders');
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->label(__('production.fields.code'))
                            ->maxLength(255)
                            ->disabled(),
                        Select::make('status')
                            ->label(__('production.fields.status'))
                            ->options([
                                'planned' => __('production.statuses.planned'),
                                'in_progress' => __('production.statuses.in_progress'),
                                'completed' => __('production.statuses.completed'),
                                'cancelled' => __('production.statuses.cancelled'),
                            ])
                            ->required(),
                    ]),
                    Grid::make(2)->components([
                        Select::make('product_id')
                            ->label(__('production.fields.product'))
                            ->options(fn () => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('quantity')
                            ->label(__('production.fields.quantity'))
                            ->numeric()
                            ->required(),
                    ]),
                    Select::make('work_order_id')
                        ->label(__('production.fields.work_order'))
                        ->options(fn () => WorkOrder::query()->orderByDesc('id')->limit(200)->pluck('code', 'id')->all())
                        ->searchable(),
                    DateTimePicker::make('due_at')
                        ->label(__('production.fields.due_at')),
                    Textarea::make('notes')
                        ->label(__('production.fields.notes'))
                        ->columnSpanFull(),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('code')->label(__('production.fields.code'))->searchable(),
                Tables\Columns\TextColumn::make('product.name')->label(__('production.fields.product'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('quantity')->label(__('production.fields.quantity'))->sortable(),
                Tables\Columns\TextColumn::make('status')->label(__('production.fields.status'))->sortable(),
                Tables\Columns\TextColumn::make('workOrder.code')->label(__('production.fields.work_order'))->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('due_at')->label(__('production.fields.due_at'))->dateTime()->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ProductionPhasesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductionOrders::route('/'),
            'create' => CreateProductionOrder::route('/create'),
            'view' => ViewProductionOrder::route('/{record}'),
            'edit' => EditProductionOrder::route('/{record}/edit'),
        ];
    }
}
