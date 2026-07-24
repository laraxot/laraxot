<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Inventory\Actions\GetInventoryOverviewRowsAction;
use Modules\Inventory\Models\InventoryOverviewRow;
use Modules\Inventory\Models\Warehouse;
use Modules\Platform\Models\PlatformSetting;

final class InventoryOverview extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = null;

    protected static ?string $title = null;

    protected static ?string $slug = 'inventory-overview';

    protected string $view = 'inventory::filament.pages.inventory-overview';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform();
    }

    public static function canAccess(): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform();
    }

    public static function getNavigationGroup(): string
    {
        return __('inventory.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('inventory.overview');
    }

    public function getTitle(): string
    {
        return __('inventory.overview_page.title');
    }

    public function table(Table $table): Table
    {
        $columns = [
            Tables\Columns\TextColumn::make('product_name')
                ->label(__('inventory.fields.product'))
                ->searchable(query: fn (Builder $query, string $search): Builder => $query->where('inventory_overview_rows.product_name', 'like', "%{$search}%"))
                ->sortable(),
            Tables\Columns\TextColumn::make('product_sku')
                ->label(__('inventory.fields.sku'))
                ->placeholder('—')
                ->searchable(query: fn (Builder $query, string $search): Builder => $query->where('inventory_overview_rows.product_sku', 'like', "%{$search}%"))
                ->toggleable(),
            Tables\Columns\TextColumn::make('warehouse_name')
                ->label(__('inventory.fields.warehouse'))
                ->searchable(query: fn (Builder $query, string $search): Builder => $query->where('inventory_overview_rows.warehouse_name', 'like', "%{$search}%"))
                ->sortable(),
        ];

        if (self::storageZonesEnabled()) {
            $columns[] = Tables\Columns\TextColumn::make('location_code')
                ->label(__('inventory.fields.location'))
                ->placeholder(__('inventory.overview_page.unassigned_location'))
                ->sortable();
        }

        $columns[] = Tables\Columns\TextColumn::make('aggregate_stock')
            ->label(__('inventory.fields.quantity'))
            ->numeric()
            ->sortable();
        $columns[] = Tables\Columns\TextColumn::make('last_movement_at')
            ->label(__('inventory.fields.last_movement_at'))
            ->dateTime()
            ->sortable()
            ->toggleable();

        $filters = [
            Tables\Filters\SelectFilter::make('warehouse_id')
                ->label(__('inventory.overview_page.filters.warehouse'))
                ->options(fn (): array => Warehouse::query()->orderBy('name')->pluck('name', 'id')->all())
                ->query(function (Builder $query, array $data): Builder {
                    $warehouseId = (int) ($data['value'] ?? 0);

                    return $warehouseId > 0
                        ? $query->where('inventory_overview_rows.warehouse_id', $warehouseId)
                        : $query;
                }),
            Tables\Filters\TernaryFilter::make('has_stock')
                ->label(__('inventory.overview_page.filters.has_stock'))
                ->placeholder('Tutti')
                ->trueLabel('Solo con giacenza')
                ->falseLabel('Solo senza giacenza')
                ->queries(
                    true: fn (Builder $query): Builder => $query->where('inventory_overview_rows.aggregate_stock', '>', 0),
                    false: fn (Builder $query): Builder => $query->where('inventory_overview_rows.aggregate_stock', '<=', 0),
                ),
        ];

        if (self::storageZonesEnabled()) {
            $filters[] = Tables\Filters\TernaryFilter::make('location_state')
                ->label(__('inventory.overview_page.filters.location_state'))
                ->placeholder('Tutte')
                ->trueLabel(__('inventory.overview_page.filters.with_location'))
                ->falseLabel(__('inventory.overview_page.filters.without_location'))
                ->queries(
                    true: fn (Builder $query): Builder => $query->whereNotNull('inventory_overview_rows.location_id'),
                    false: fn (Builder $query): Builder => $query->whereNull('inventory_overview_rows.location_id'),
                );
        }

        return $table
            ->query($this->overviewRowsQuery())
            ->columns($columns)
            ->filters($filters)
            ->defaultSort('aggregate_stock', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }

    /**
     * @return array<string, int>
     */
    public function getHeaderStats(): array
    {
        $rows = $this->overviewRowsQuery()->get();

        return [
            'products' => (int) $rows->pluck('product_id')->unique()->count(),
            'warehouses' => (int) $rows->pluck('warehouse_id')->unique()->count(),
            'locations' => (int) $rows->pluck('location_id')->filter()->unique()->count(),
            'total_stock' => (int) $rows->sum('aggregate_stock'),
        ];
    }

    /** @return Builder<InventoryOverviewRow> */
    private function overviewRowsQuery(): Builder
    {
        $rows = app(GetInventoryOverviewRowsAction::class)->execute(
            groupByLocation: self::storageZonesEnabled(),
        );

        return InventoryOverviewRow::query()
            ->fromSub($rows, 'inventory_overview_rows')
            ->select('inventory_overview_rows.*');
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.inventory',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    private static function isEnabledByPlatform(): bool
    {
        return PlatformSetting::getBool('inventory.enable_realtime_monitoring', true);
    }

    private static function storageZonesEnabled(): bool
    {
        return PlatformSetting::getBool('inventory.enable_storage_zones', false);
    }
}
