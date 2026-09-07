<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Modules\Catalog\Filament\Resources\CatalogPartTypeResource\Pages;
use Modules\Catalog\Models\CatalogPartType;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class CatalogPartTypeResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = CatalogPartType::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    /** @var string */
    protected const ACCOUNT_FEATURE_ADMIN = 'admin.catalog';

    /** @var string */
    protected const ACCOUNT_FEATURE_OPERATOR = 'operator.catalog';

    public static function getNavigationGroup(): string
    {
        return __('catalog.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('catalog.part_type');
    }

    public static function getPluralModelLabel(): string
    {
        return __('catalog.part_types');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogPartTypes::route('/'),
            'create' => Pages\CreateCatalogPartType::route('/create'),
            'view' => Pages\ViewCatalogPartType::route('/{record}'),
            'edit' => Pages\EditCatalogPartType::route('/{record}/edit'),
        ];
    }
}
