<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Catalog\Filament\Resources\CatalogModelResource\Pages;
use Modules\Catalog\Models\CatalogModel;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class CatalogModelResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = CatalogModel::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

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
        return __('catalog.model');
    }

    public static function getPluralModelLabel(): string
    {
        return __('catalog.models');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCatalogModels::route('/'),
            'create' => Pages\CreateCatalogModel::route('/create'),
            'view' => Pages\ViewCatalogModel::route('/{record}'),
            'edit' => Pages\EditCatalogModel::route('/{record}/edit'),
        ];
    }
}
