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
use Modules\Platform\Filament\Resources\GatedXotBaseResource;
use Modules\Signage\Filament\Resources\SignPriceListResource\Pages;
use Modules\Signage\Models\SignPriceList;

/**
 * Sales price lists, kept per brand/customer the way the customer keeps them.
 * The grouping is organisational: an article has one sale price, discounts live
 * on the quotation line.
 */
final class SignPriceListResource extends GatedXotBaseResource
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

    /**
     * @return array<string, Component|Field>
     */
    public static function getFormSchema(): array
    {
        return [
            'main' => Section::make()
                ->columnSpanFull()
                ->components([
                    Grid::make(2)->components([
                        TextInput::make('code')
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('name')
                            ->required(),
                    ]),
                    Grid::make(3)->components([
                        TextInput::make('brand'),
                        Select::make('customer_id')
                            ->options(fn (): array => Customer::query()->orderBy('name')->pluck('name', 'id')->all())
                            ->searchable()
                            ->preload(),
                        TextInput::make('default_markup_percent')
                            ->helperText(__('signage::signage.pricing.markup_hint'))
                            ->numeric()
                            ->suffix('%'),
                    ]),
                    Toggle::make('is_active')->default(true),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('brand')->placeholder('—')->searchable(),
                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items'),
                Tables\Columns\TextColumn::make('default_markup_percent')
                    ->placeholder('—')
                    ->suffix('%'),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSignPriceLists::route('/'),
        ];
    }
}
