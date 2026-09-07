<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Field;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Customer\Models\Customer;
use Modules\Signage\Filament\Resources\SignPriceListResource\Pages;
use Modules\Signage\Models\SignPriceList;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Sales price lists, kept per brand/customer the way the customer keeps them.
 * The grouping is organisational: an article has one sale price, discounts live
 * on the quotation line.
 */
final class SignPriceListResource extends XotBaseResource
{
    protected static ?string $model = SignPriceList::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): string
    {
        return (string) __('signage::signage.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('signage::signage.sales_price_list');
    }

    public static function getPluralModelLabel(): string
    {
        return __('signage::signage.sales_price_lists');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignPriceLists::route('/'),
        ];
    }
}
