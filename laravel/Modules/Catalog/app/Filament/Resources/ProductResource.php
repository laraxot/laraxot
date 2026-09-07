<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Modules\Catalog\Filament\Resources\ProductResource\Pages;
use Modules\Catalog\Models\Product;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class ProductResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected const string ACCOUNT_FEATURE_ADMIN = 'admin.catalog';

    protected const string ACCOUNT_FEATURE_OPERATOR = 'operator.catalog';

    public static function getNavigationGroup(): string
    {
        return __('catalog.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('catalog.product');
    }

    public static function getPluralModelLabel(): string
    {
        return __('catalog.products');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
