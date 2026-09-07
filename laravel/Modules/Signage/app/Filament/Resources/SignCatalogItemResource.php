<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignPriceList;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * A finished product in a sales price list, with its own distinta. Items are
 * exploded rather than parametric — one row per letter-height band, LED
 * temperature and colour — so "Duplica" is how the next variant is born, the
 * same as in the customer's gestionale.
 */
final class SignCatalogItemResource extends XotBaseResource
{
    protected static ?string $model = SignCatalogItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?int $navigationSort = 40;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('signage::signage.catalog_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('signage::signage.catalog_items');
    }

    public static function getRelations(): array
    {
        return [
            SignCatalogItemResource\RelationManagers\ComponentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignCatalogItems::route('/'),
            'view' => Pages\ViewSignCatalogItem::route('/{record}'),
        ];
    }
}
