<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Modules\Catalog\Filament\Resources\CatalogMeasureUnitResource\Pages;
use Modules\Catalog\Models\CatalogMeasureUnit;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class CatalogMeasureUnitResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = CatalogMeasureUnit::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-scale';

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
        return __('catalog.measure_unit');
    }

    public static function getPluralModelLabel(): string
    {
        return __('catalog.measure_units');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogMeasureUnits::route('/'),
            'create' => Pages\CreateCatalogMeasureUnit::route('/create'),
            'view' => Pages\ViewCatalogMeasureUnit::route('/{record}'),
            'edit' => Pages\EditCatalogMeasureUnit::route('/{record}/edit'),
        ];
    }
}
