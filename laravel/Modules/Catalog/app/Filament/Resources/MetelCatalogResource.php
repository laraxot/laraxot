<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources;

use App\Support\Concerns\HasAccountFeatureVisibility;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Catalog\Actions\FetchMetelCatalogViaApiAction;
use Modules\Catalog\Actions\ImportMetelPriceListFileAction;
use Modules\Catalog\Filament\Resources\MetelCatalogResource\Pages;
use Modules\Catalog\Filament\Resources\MetelCatalogResource\RelationManagers\ItemsRelationManager;
use Modules\Catalog\Models\MetelCatalog;
use Modules\Xot\Filament\Resources\XotBaseResource;
use RuntimeException;

final class MetelCatalogResource extends XotBaseResource
{
    use HasAccountFeatureVisibility;

    protected static ?string $model = MetelCatalog::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    /** @var string */
    protected const ACCOUNT_FEATURE_ADMIN = 'admin.metel_catalogs';

    /** @var string */
    protected const ACCOUNT_FEATURE_OPERATOR = 'operator.metel_catalogs';

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
