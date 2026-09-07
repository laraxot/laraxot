<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources;

use App\Support\AccountFeatures;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages\CreateInventoryMovement;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages\EditInventoryMovement;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages\ListInventoryMovements;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Pages\ViewInventoryMovement;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class InventoryMovementResource extends XotBaseResource
{
    protected static ?string $model = InventoryMovement::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function canView(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canView($record);
    }

    public static function getNavigationGroup(): string
    {
        return __('inventory.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('inventory.movement');
    }

    public static function getPluralModelLabel(): string
    {
        return __('inventory.movements');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventoryMovements::route('/'),
            'create' => CreateInventoryMovement::route('/create'),
            'view' => ViewInventoryMovement::route('/{record}'),
            'edit' => EditInventoryMovement::route('/{record}/edit'),
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
}
