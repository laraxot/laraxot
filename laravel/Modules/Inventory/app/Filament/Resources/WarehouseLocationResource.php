<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource\Pages;
use Modules\Inventory\Models\Warehouse;
use Modules\Inventory\Models\WarehouseLocation;
use Modules\Platform\Models\PlatformSetting;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class WarehouseLocationResource extends XotBaseResource
{
    protected static ?string $model = WarehouseLocation::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform() && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && self::isEnabledByPlatform() && parent::canView($record);
    }

    public static function getNavigationGroup(): string
    {
        return __('inventory.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('inventory.location');
    }

    public static function getPluralModelLabel(): string
    {
        return __('inventory.locations');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWarehouseLocations::route('/'),
            'create' => Pages\CreateWarehouseLocation::route('/create'),
            'view' => Pages\ViewWarehouseLocation::route('/{record}'),
            'edit' => Pages\EditWarehouseLocation::route('/{record}/edit'),
        ];
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
        return PlatformSetting::getBool('inventory.enable_storage_zones', false);
    }
}
