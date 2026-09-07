<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Bom\Filament\Resources\BomResource\Pages\CreateBom;
use Modules\Bom\Filament\Resources\BomResource\Pages\EditBom;
use Modules\Bom\Filament\Resources\BomResource\Pages\ListBoms;
use Modules\Bom\Filament\Resources\BomResource\Pages\ViewBom;
use Modules\Bom\Filament\Resources\BomResource\RelationManagers\BomItemsRelationManager;
use Modules\Bom\Models\Bom;
use Modules\Catalog\Models\Product;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class BomResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Bom::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    /** @var string */
    protected const ACCOUNT_FEATURE_ADMIN = 'admin.bom';

    /** @var null */
    protected const ACCOUNT_FEATURE_OPERATOR = null;

    /** @var bool */
    protected const ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;

    public static function getNavigationGroup(): string
    {
        return __('boms.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('boms.bom');
    }

    public static function getPluralModelLabel(): string
    {
        return __('boms.boms');
    }

    public static function getRelations(): array
    {
        return [
            BomItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBoms::route('/'),
            'create' => CreateBom::route('/create'),
            'view' => ViewBom::route('/{record}'),
            'edit' => EditBom::route('/{record}/edit'),
        ];
    }
}
