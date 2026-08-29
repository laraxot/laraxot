<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources;

use App\Support\AccountFeatures;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Resources\TimberCustomerPriceResource\Pages;
use Modules\Timber\Models\TimberCustomerPrice;

final class TimberCustomerPriceResource extends XotBaseResource
{
    protected static ?string $model = TimberCustomerPrice::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-currency-euro';

    protected static ?string $navigationLabel = 'Prezzi per cliente';

    protected static ?int $navigationSort = 88;

    public static function shouldRegisterNavigation(): bool
    {
        return self::isAvailableInCurrentPanel();
    }

    public static function canViewAny(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canViewAny();
    }

    public static function canCreate(): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canCreate();
    }

    public static function canEdit(Model $record): bool
    {
        return self::isAvailableInCurrentPanel() && parent::canEdit($record);
    }

    public static function getNavigationGroup(): string
    {
        return 'Anagrafiche';
    }

    private static function isAvailableInCurrentPanel(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'registries',
            operatorFeature: 'registries',
        );
    }

    public static function getModelLabel(): string
    {
        return 'Prezzo cliente';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Prezzi per cliente';
    }

    /** @return array<int|string, \Filament\Schemas\Components\Component> */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columns(3)
                ->components([
                    Select::make('customer_id')
                        ->label('Cliente')
                        ->relationship('customer', 'business_name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('product_id')
                        ->label('Prodotto')
                        ->relationship('product', 'description')
                        ->searchable()
                        ->preload()
                        ->required(),
                    TextInput::make('net_price')
                        ->label('Prezzo netto')
                        ->numeric()
                        ->minValue(0)
                        ->required(),
                ]),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer.business_name')
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.description')
                    ->label('Prodotto')
                    ->searchable(),
                Tables\Columns\TextColumn::make('net_price')
                    ->label('Prezzo netto')
                    ->money('EUR'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'business_name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTimberCustomerPrices::route('/'),
            'create' => Pages\CreateTimberCustomerPrice::route('/create'),
            'edit' => Pages\EditTimberCustomerPrice::route('/{record}/edit'),
        ];
    }
}
