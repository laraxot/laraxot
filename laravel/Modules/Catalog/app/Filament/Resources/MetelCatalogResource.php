<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;
use Modules\Catalog\Filament\Resources\MetelCatalogResource\RelationManagers\ItemsRelationManager;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class MetelCatalogResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = MetelCatalog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected const string ACCOUNT_FEATURE_ADMIN = 'admin.metel_catalogs';

    protected const string ACCOUNT_FEATURE_OPERATOR = 'operator.metel_catalogs';

    public static function getNavigationGroup(): string
    {
        return __('metel.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('metel.catalog');
    }

    public static function getPluralModelLabel(): string
    {
        return __('metel.catalogs');
    }

    public static function getRelations(): array
    {
        return [
            ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMetelCatalogs::route('/'),
            'create' => Pages\CreateMetelCatalog::route('/create'),
            'view' => Pages\ViewMetelCatalog::route('/{record}'),
            'edit' => Pages\EditMetelCatalog::route('/{record}/edit'),
        ];
    }
}
