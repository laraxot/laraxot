<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberProductResource\Pages;
use Modules\Timber\Models\TimberProduct;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class TimberProductResource extends XotBaseResource
{
    protected static ?string $model = TimberProduct::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'Prodotti';

    protected static ?int $navigationSort = 86;

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
        return 'Anagrafiche';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'registries',
            operatorFeature: 'registries',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Prodotto';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Prodotti';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberProducts::route('/'),
            'create' => Pages\CreateTimberProduct::route('/create'),
            'view' => Pages\ViewTimberProduct::route('/{record}'),
            'edit' => Pages\EditTimberProduct::route('/{record}/edit'),
        ];
    }
}
