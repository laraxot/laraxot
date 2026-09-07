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
use Modules\Production\Filament\Resources\ProductionOrderResource\Pages\CreateProductionOrder;
use Modules\Production\Filament\Resources\ProductionOrderResource\Pages\EditProductionOrder;
use Modules\Production\Filament\Resources\ProductionOrderResource\Pages\ListProductionOrders;
use Modules\Production\Filament\Resources\ProductionOrderResource\Pages\ViewProductionOrder;
use Modules\Production\Filament\Resources\ProductionOrderResource\RelationManagers\ProductionPhasesRelationManager;
use Modules\Production\Models\ProductionOrder;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class ProductionOrderResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = ProductionOrder::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    /** @var string */
    private const ACCOUNT_FEATURE_ADMIN = 'admin.production';

    /** @var null */
    private const ACCOUNT_FEATURE_OPERATOR = null;

    /** @var bool */
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
