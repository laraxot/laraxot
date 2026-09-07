<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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
use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Filament\Resources\SignPriceListItemResource\Pages;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Support\SignageCosting;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * The supplier price list: what every material, machining step and outsourced
 * job costs, grouped by the work centre it belongs to. It is the cost side —
 * the sales side lives in SignPriceListResource.
 */
final class SignPriceListItemResource extends XotBaseResource
{
    protected static ?string $model = SignPriceListItem::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('signage::signage.supplier_item');
    }

    public static function getPluralModelLabel(): string
    {
        return __('signage::signage.supplier_items');
    }

    public static function getRelations(): array
    {
        return [
            SignPriceListItemResource\RelationManagers\PriceEntriesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignPriceListItems::route('/'),
            'view' => Pages\ViewSignPriceListItem::route('/{record}'),
        ];
    }
}
