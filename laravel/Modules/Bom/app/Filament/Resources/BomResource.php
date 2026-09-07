<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Modules\Bom\Filament\Resources\BomResource\Pages\CreateBom;
use Modules\Bom\Filament\Resources\BomResource\Pages\EditBom;
use Modules\Bom\Filament\Resources\BomResource\Pages\ListBoms;
use Modules\Bom\Filament\Resources\BomResource\Pages\ViewBom;
use Modules\Bom\Filament\Resources\BomResource\RelationManagers\BomItemsRelationManager;
use Modules\Bom\Models\Bom;
use Modules\Xot\Filament\Resources\XotBaseResource;

final class BomResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = Bom::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-list-bullet';

    protected const string ACCOUNT_FEATURE_ADMIN = 'admin.bom';

    protected const ?string ACCOUNT_FEATURE_OPERATOR = null;

    protected const bool ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;

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
