<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Models\TimberStockMovement;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberStockMovementResource extends XotBaseResource
{
    protected static ?string $model = TimberStockMovement::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-up-down';

    protected static ?string $navigationLabel = 'Movimenti magazzino';

    protected static ?int $navigationSort = 40;

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

    public static function getNavigationGroup(): string
    {
        return 'Magazzino';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'access',
            operatorFeature: 'access',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Movimento';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Movimenti magazzino';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberStockMovements::route('/'),
            'create' => Pages\CreateTimberStockMovement::route('/create'),
            'view' => Pages\ViewTimberStockMovement::route('/{record}'),
        ];
    }
}
